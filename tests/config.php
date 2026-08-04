<?php

declare(strict_types=1);

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
        'config_glob_paths' => [
            __DIR__ . '/testing.config.php',
        ],
        'module_paths' => [],
        // Modules are autoloaded by Composer, so laminas-loader is not needed.
        'use_laminas_loader' => false,
    ],
];
