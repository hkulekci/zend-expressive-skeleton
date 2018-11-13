<?php
return [
    'dependencies' => [
        'factories' => [
            CoreComponent\Service\Logger::class   => \CoreComponent\Service\LoggerFactory::class,
        ],
        'initializers' => [
            \CoreComponent\Service\Initializers\LoggerAwareInitializer::class,
        ]
    ],
    // DOCTRINE - PERSISTENCY
    'doctrine' => [
        'annotation' => [
            'metadata' => [
                'component/CoreComponent/Entity'
            ],
        ],
    ],
];
