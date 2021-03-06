<?php
/**
 * Abstract application service class for derived logger-enabled services.
 *
 * @since     Jan 2016
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Service;

use DoctrineComponent\Doctrine\ObjectManagerAware;
use DoctrineComponent\Doctrine\ObjectManagerAwareTrait;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;

class AbstractService implements LoggerAwareInterface, ObjectManagerAware
{
    use LoggerAwareTrait;
    use ObjectManagerAwareTrait;
}
