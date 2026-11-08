<?php

$modules = [
    'Laminas\Hydrator',
    'Laminas\Paginator',
    'Laminas\Validator',
    'DoctrineModule',
    'DoctrineORMModule',
];

// laminas-form is optional: load its module only when it is installed.
if (class_exists(\Laminas\Form\Module::class)) {
    array_unshift($modules, 'Laminas\Form');
}

return [
    'modules' => $modules,
    'module_listener_options' => [
        'module_paths' => [],
        // Modules are autoloaded by Composer, so laminas-loader is not needed.
        'use_laminas_loader' => false,
        'config_glob_paths' => [
            __DIR__ . '/module.config.php',
            __DIR__ . '/../ci/config/ci.config.php',
        ],
    ],
];
