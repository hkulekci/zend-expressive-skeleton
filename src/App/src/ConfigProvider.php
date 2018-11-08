<?php

declare(strict_types=1);

namespace App;

use App\Authentication\AuthenticationMiddleware;
use App\Authentication\AuthenticationMiddlewareFactory;
use App\Authentication\LoginPageHandler;
use App\Authentication\LoginPageHandlerFactory;
use App\Authentication\LogoutPageHandler;
use App\ErrorHandler\LoggingErrorListenerFactory;
use Zend\Stratigility\Middleware\ErrorHandler;

class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'routes'       => $this->getRoutes(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'delegators' => [
                ErrorHandler::class => [
                    LoggingErrorListenerFactory::class,
                ],
            ],
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
                LogoutPageHandler::class => LogoutPageHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                LoginPageHandler::class => LoginPageHandlerFactory::class,
                AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                '__main__' => [__DIR__ . '/../templates'],
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }

    public function getRoutes() : array
    {
        return [
            [
                'name'            => 'home',
                'path'            => '/',
                'middleware'      => Handler\HomePageHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'name'            => 'api.ping',
                'path'            => '/api/ping',
                'middleware'      => Handler\PingHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'name'            => 'login',
                'path'            => '/login',
                'middleware'      => LoginPageHandler::class,
                'allowed_methods' => ['GET', 'POST'],
            ],
            [
                'name'            => 'logout',
                'path'            => '/logout',
                'middleware'      => LogoutPageHandler::class,
                'allowed_methods' => ['GET'],
            ],
        ];
    }
}
