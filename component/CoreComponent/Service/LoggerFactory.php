<?php

namespace CoreComponent\Service;

use Interop\Container\ContainerInterface;
use Zend\Log\Logger;
use Zend\Log\Processor\PsrPlaceholder;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\Factory\FactoryInterface;

class LoggerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     * @throws \Zend\Log\Exception\RuntimeException
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger = $container->get(Logger::class);
        $logger->addProcessor(new PsrPlaceholder());
        $writer = new Stream('data/logs/'.date('Y-m-d').'-application.log');
        $logger->addWriter($writer);

        return $logger;
    }
}
