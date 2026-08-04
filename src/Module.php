<?php

declare(strict_types=1);

namespace DoctrineORMModule;

use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\DependencyIndicatorInterface;
use Laminas\ModuleManager\Feature\InitProviderInterface;
use Laminas\ModuleManager\ModuleManagerInterface;

/**
 * Base module for Doctrine ORM.
 */
final class Module implements
    ConfigProviderInterface,
    DependencyIndicatorInterface,
    InitProviderInterface
{
    public function init(ModuleManagerInterface $manager): void
    {
        // Initialize the console
        $manager
            ->getEventManager()
            ->getSharedManager()
            ->attach(
                'doctrine',
                'loadCli.post',
                static function (EventInterface $event): void {
                    $event
                        ->getParam('ServiceManager')
                        ->get(CliConfigurator::class)
                        ->configure($event->getTarget());
                },
                1,
            );
    }

    /**
     * {@inheritDoc}
     *
     * @return array<array-key,mixed>
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getModuleDependencies(): array
    {
        return ['DoctrineModule'];
    }
}
