<?php
return [
    'dependencies' => [
        'abstract_factories' => [
            \CoreComponent\Doctrine\DoctrineFactory::class,
        ],
        'factories' => [
            Doctrine\Common\Cache\Cache::class    => \CoreComponent\Doctrine\DoctrineFileCacheFactory::class,
            Doctrine\Common\Cache\Cache::class    => \CoreComponent\Doctrine\DoctrineArrayCacheFactory::class,
            CoreComponent\Service\Logger::class   => \CoreComponent\Service\LoggerFactory::class,
        ],
        'initializers' => [
            \CoreComponent\Doctrine\ObjectManagerAwareInitializer::class,
            \CoreComponent\Service\Initializers\LoggerAwareInitializer::class,
        ]
    ],
    // DOCTRINE - PERSISTENCY
    'doctrine' => [
        'driver' => [
            'annotation_driver' => [
                'class' => Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                ],
            ],

            'orm_default' => [
                'drivers' => [
                ],
            ],
        ],

        'annotation' => [
            'metadata' => [
                'component/CoreComponent/Entity'
            ],
        ],
    ],
];
