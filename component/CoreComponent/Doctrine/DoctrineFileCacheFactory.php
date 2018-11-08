<?php
/**
 * Doctrine File Cache Factory
 *
 * @since     Sep 2016
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Doctrine;

use Doctrine\Common\Cache\FilesystemCache;
use Interop\Container\ContainerInterface;

class DoctrineFileCacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new FilesystemCache('data/cache/annotation');
    }
}
