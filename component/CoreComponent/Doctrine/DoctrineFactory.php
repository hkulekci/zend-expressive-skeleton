<?php
/**
 * Doctrine Factory
 *
 * @since     Sep 2016
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Doctrine;


use CoreComponent\Doctrine\MySql\DateCast as MySqlDateCast;
use CoreComponent\Doctrine\MySql\IntCast as MySqlIntCast;
use CoreComponent\Doctrine\PgSql\DateCast as PgSqlDateCast;
use CoreComponent\Doctrine\PgSql\IntCast as PgSqlIntast;
use CoreComponent\Doctrine\Sqlite\DateCast as SqliteDateCast;
use CoreComponent\Doctrine\Sqlite\IntCast as SqliteIntast;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSqlDriver;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as PDOSqliteSqlDriver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class DoctrineFactory implements AbstractFactoryInterface
{
    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config   = $container->has('config') ? $container->get('config') : [];

        return isset($config['doctrine']['connection'][$requestedName]);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config   = $container->has('config') ? $container->get('config') : [];
        $proxyDir = $config['doctrine']['orm']['proxy_dir'] ?? 'data/cache/EntityProxy';
        $proxyNamespace = $config['doctrine']['orm']['proxy_namespace'] ?? 'EntityProxy';
        $autoGenerateProxyClasses = $config['doctrine']['orm']['auto_generate_proxy_classes'] ?? false;
        $underscoreNamingStrategy = $config['doctrine']['orm']['underscore_naming_strategy'] ?? false;

        // Doctrine ORM
        $doctrine = new Configuration();
        $doctrine->setProxyDir($proxyDir);
        $doctrine->setProxyNamespace($proxyNamespace);
        $doctrine->setAutoGenerateProxyClasses($autoGenerateProxyClasses);
        if ($underscoreNamingStrategy) {
            $doctrine->setNamingStrategy(new UnderscoreNamingStrategy());
        }

        // ORM mapping by Annotation
        AnnotationRegistry::registerFile('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
        $driver = new AnnotationDriver(
            new AnnotationReader(),
            $config['doctrine']['annotation']['metadata']
        );
        $doctrine->setMetadataDriverImpl($driver);

        //TODO: check the configuration file to be able to implement development configuration
        $cache = $container->get(Cache::class);
        $doctrine->setQueryCacheImpl($cache);
        $doctrine->setHydrationCacheImpl($cache);
        $doctrine->setResultCacheImpl($cache);
        $doctrine->setMetadataCacheImpl($cache);

        $doctrineConfig = $config['doctrine']['connection'][$requestedName];


        if ($doctrineConfig['driverClass'] === PDOPgSqlDriver::class) {
            $doctrine->addCustomDatetimeFunction('datecast', PgSqlDateCast::class);
            $doctrine->addCustomNumericFunction('INT', PgSqlIntast::class);
        } elseif ($doctrineConfig['driverClass'] === PDOMySqlDriver::class) {
            $doctrine->addCustomDatetimeFunction('datecast', MySqlDateCast::class);
            $doctrine->addCustomNumericFunction('INT', MySqlIntCast::class);
        } elseif ($doctrineConfig['driverClass'] === PDOSqliteSqlDriver::class) {
            $doctrine->addCustomDatetimeFunction('datecast', SqliteDateCast::class);
            $doctrine->addCustomNumericFunction('INT', SqliteIntast::class);
        }

        if ($doctrineConfig['driverClass'] === PDOPgSqlDriver::class) {
            $doctrine->addCustomDatetimeFunction('datecast', PgSqlDateCast::class);
            $doctrine->addCustomNumericFunction('INT', PgSqlIntast::class);
        } elseif ($doctrineConfig['driverClass'] === PDOMySqlDriver::class) {
            $doctrine->addCustomDatetimeFunction('datecast', MySqlDateCast::class);
            $doctrine->addCustomNumericFunction('INT', MySqlIntCast::class);
        }

        // EntityManager
        return EntityManager::create($doctrineConfig, $doctrine);
    }
}
