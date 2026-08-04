<?php

declare(strict_types=1);

namespace DoctrineORMModule\Service;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Service\AbstractFactory;
use DoctrineORMModule\Options\EntityManager as DoctrineORMModuleEntityManager;
use Psr\Container\ContainerInterface;

use function assert;

final class EntityManagerFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     *
     * @param string $requestedName
     *
     * @return EntityManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array|null $options = null)
    {
        $options = $this->getOptions($container, 'entitymanager');
        assert($options instanceof DoctrineORMModuleEntityManager);
        $connection = $container->get($options->getConnection());
        $config     = $container->get($options->getConfiguration());

        // The entity resolver factory attaches ResolveTargetEntityListener and
        // returns the event manager it registered it on.
        $eventManager = $container->get($options->getEntityResolver());

        // EntityManager::create() was removed in ORM 3; the constructor is
        // public in both ORM 2.20 and ORM 3. The event manager has to be passed
        // explicitly: DBAL 4 dropped Connection::getEventManager(), so the
        // entity manager would otherwise build an empty one and the resolver
        // would never see onClassMetadataNotFound.
        return new EntityManager($connection, $config, $eventManager);
    }

    public function getOptionsClass(): string
    {
        return DoctrineORMModuleEntityManager::class;
    }
}
