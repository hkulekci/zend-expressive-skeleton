<?php

declare(strict_types=1);

namespace App\Handler;

use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;

class HomePageHandler implements RequestHandlerInterface
{
    private $template;
    private $repository;

    public function __construct(
        Template\TemplateRendererInterface $template,
        EntityRepository $repository
    ) {
        $this->template = $template;
        $this->repository = $repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = [
            'settings' => array_map(
                function($element) {
                    return $element->toArray();
                },
                $this->repository->findAll()
            ),
        ];
        return new HtmlResponse($this->template->render('app/home-page', $data));
    }
}
