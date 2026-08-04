<?php

declare(strict_types=1);

namespace DoctrineORMModuleTest;

use Laminas\EventManager\EventManager;
use Laminas\EventManager\SharedEventManager;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;
use Laminas\ModuleManager\Listener\DefaultListenerAggregate;
use Laminas\ModuleManager\Listener\ListenerOptions;
use Laminas\ModuleManager\Listener\ServiceListener;
use Laminas\ModuleManager\ModuleEvent;
use Laminas\ModuleManager\ModuleManager;
use Laminas\ServiceManager\ServiceManager;

/**
 * Utility used to retrieve a freshly bootstrapped application's service manager
 *
 * The module manager is wired up directly here rather than through laminas-mvc,
 * which this module no longer depends on.
 *
 * @link    http://www.doctrine-project.org/
 */
class ServiceManagerFactory
{
    /**
     * Builds a new ServiceManager instance
     *
     * @param  mixed[]|null $configuration
     */
    public static function getServiceManager(array|null $configuration = null): ServiceManager
    {
        $configuration = $configuration ?: include __DIR__ . '/config.php';

        $serviceManager = new ServiceManager();
        $serviceManager->setService('ApplicationConfig', $configuration);

        $sharedEvents = new SharedEventManager();
        $events       = new EventManager($sharedEvents);

        // laminas-mvc registered these. DoctrineModule's CliFactory resolves
        // "EventManager" from the container, and this module attaches its
        // loadCli.post handler to the shared manager during init(), so both have
        // to be backed by the same SharedEventManager the module manager uses.
        $serviceManager->setService('SharedEventManager', $sharedEvents);
        $serviceManager->setFactory(
            'EventManager',
            static fn (): EventManager => new EventManager($sharedEvents),
        );
        $serviceManager->setShared('EventManager', false);

        $serviceListener = new ServiceListener($serviceManager);
        $serviceListener->addServiceManager(
            $serviceManager,
            'service_manager',
            ServiceProviderInterface::class,
            'getServiceConfig',
        );
        $serviceManager->setService('ServiceListener', $serviceListener);

        $defaultListeners = new DefaultListenerAggregate(
            new ListenerOptions($configuration['module_listener_options'] ?? []),
        );
        $defaultListeners->attach($events);
        $serviceListener->attach($events);

        $moduleEvent = new ModuleEvent();
        $moduleEvent->setParam('ServiceManager', $serviceManager);

        $moduleManager = new ModuleManager($configuration['modules'], $events);
        $moduleManager->setEvent($moduleEvent);
        $moduleManager->loadModules();

        // laminas-mvc would normally expose the merged module configuration as
        // the "config" service; do the same here.
        $serviceManager->setService(
            'config',
            $moduleEvent->getConfigListener()->getMergedConfig(false),
        );

        return $serviceManager;
    }
}
