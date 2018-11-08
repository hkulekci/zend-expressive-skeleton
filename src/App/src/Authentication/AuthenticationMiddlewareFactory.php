<?php
declare(strict_types=1);

namespace App\Authentication;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AuthenticationMiddleware
    {
        $template = $container->get(TemplateRendererInterface::class);

        return new AuthenticationMiddleware($template);
    }
}
