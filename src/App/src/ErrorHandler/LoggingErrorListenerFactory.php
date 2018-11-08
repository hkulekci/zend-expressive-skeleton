<?php
namespace App\ErrorHandler;

use CoreComponent\Service\Logger;
use Psr\Container\ContainerInterface;
use Zend\Stratigility\Middleware\ErrorHandler;

class LoggingErrorListenerFactory
{
    /**
     * @param ContainerInterface $container
     * @param $serviceName
     * @param callable $callback
     * @return ErrorHandler
     */
    public function __invoke(
        ContainerInterface $container,
        $serviceName,
        callable $callback
    ) : ErrorHandler {
        $logger = $container->get(Logger::class);
        $listener = new LoggingErrorListener($logger);

        $errorHandler = $callback();
        $errorHandler->attachListener($listener);
        return $errorHandler;
    }
}
