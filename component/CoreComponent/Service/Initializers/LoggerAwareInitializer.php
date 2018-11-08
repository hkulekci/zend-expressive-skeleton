<?php
/**
 * Logger Aware Interface
 *
 * @since     Sep 2015
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Service\Initializers;

use CoreComponent\Service\Logger;
use Interop\Container\ContainerInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerAwareInitializer implements InitializerInterface
{
    /**
     * Initialize method for the logger services.
     * Runs when service manager created a new instance of a service which implements ServiceLocatorAwareInterface.
     *
     * @see config > service_manager > initializer key for details.
     *
     * @param                         $instance
     * @param ServiceLocatorInterface $services
     */
    public function initialize($instance, ServiceLocatorInterface $services): void
    {
        if ($instance instanceof LoggerAwareInterface) {
            $instance->setLogger($services->get(Logger::class));
        }
    }

    /**
     * Initialize the given instance
     *
     * @param  ContainerInterface $container
     * @param  object             $instance
     *
     * @return void
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        return $this->initialize($instance, $container);
    }
}
