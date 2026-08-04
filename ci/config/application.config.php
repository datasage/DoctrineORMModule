<?php

return [
    'modules' => [
        'Laminas\Form',
        'Laminas\Hydrator',
        'Laminas\Paginator',
        'Laminas\Validator',
        'DoctrineModule',
        'DoctrineORMModule',
    ],
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
