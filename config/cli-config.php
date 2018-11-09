<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';
/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
/** @var \Zend\ServiceManager\ServiceManager $container */
$container->setFactory(\Doctrine\Common\Cache\Cache::class, \CoreComponent\Doctrine\DoctrineArrayCacheFactory::class);
// to handle cache problems
/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get('orm_default');

return ConsoleRunner::createHelperSet($em);
