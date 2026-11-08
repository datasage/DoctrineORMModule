<?php

declare(strict_types=1);

use Laminas\Form\Module as FormModule;

$modules = [
    'Laminas\Hydrator',
    'Laminas\Paginator',
    'Laminas\Validator',
    'DoctrineModule',
    'DoctrineORMModule',
];

// laminas-form is optional: load its module only when it is installed.
if (class_exists(FormModule::class)) {
    array_unshift($modules, 'Laminas\Form');
}

return [
    'modules' => $modules,
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/testing.config.php',
        ],
        'module_paths' => [],
        // Modules are autoloaded by Composer, so laminas-loader is not needed.
        'use_laminas_loader' => false,
    ],
];
