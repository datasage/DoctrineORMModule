<?php

declare(strict_types=1);

namespace DoctrineORMModule\Service;

use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use DoctrineORMModule\CliConfigurator;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * Builds the EntityManagerProvider that ORM 3 console commands are constructed
 * with. The manager is chosen the same way CliConfigurator picks it, so the
 * --object-manager option keeps working.
 */
final class EntityManagerProviderFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array|null $options = null,
    ): EntityManagerProvider {
        return new SingleManagerProvider($container->get(CliConfigurator::resolveObjectManagerName()));
    }
}
