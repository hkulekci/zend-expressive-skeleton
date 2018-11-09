<?php
declare(strict_types=1);

namespace App\Handler;

use CoreComponent\Entity\ApplicationSetting;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $applicationSetting = $container->get('orm_default')->getRepository(ApplicationSetting::class);

        return new HomePageHandler(
            $container->get(TemplateRendererInterface::class),
            $applicationSetting
        );
    }
}
