<?php

declare(strict_types=1);

namespace DoctrineORMModule\Service;

use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

use function is_string;
use function sprintf;

/**
 * Builds ORM console commands that require an EntityManagerProvider.
 *
 * ORM 2 commands were constructed with no arguments and found their entity
 * manager through the console HelperSet. ORM 3 removed the helper set and takes
 * the provider as a constructor argument instead, so those commands can no
 * longer be registered as invokables.
 */
final class EntityManagerCommandFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array|null $options = null): Command
    {
        $config = $container->get('config');
        $class  = $config['doctrine']['orm_commands'][$requestedName] ?? null;

        if (! is_string($class)) {
            throw new ServiceNotCreatedException(sprintf(
                'No ORM command class is mapped for "%s" in "doctrine.orm_commands".',
                $requestedName,
            ));
        }

        return new $class($container->get(EntityManagerProvider::class));
    }
}
