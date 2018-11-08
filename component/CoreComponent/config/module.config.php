<?php
return [
    'dependencies' => [
        'factories' => [
            Doctrine\Common\Cache\Cache::class    => \CoreComponent\Doctrine\DoctrineFileCacheFactory::class,
            Doctrine\Common\Cache\Cache::class    => \CoreComponent\Doctrine\DoctrineArrayCacheFactory::class,
            Doctrine\ORM\EntityManager::class     => \CoreComponent\Doctrine\DoctrineFactory::class,
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
