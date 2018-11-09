<?php
declare(strict_types=1);

namespace App\Authentication;

use CoreComponent\Entity\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template\TemplateRendererInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    public const AUTH_SESSION_KEY = 'auth_session_key';

    private $template;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return RedirectResponse
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if ($request->getUri()->getPath() === '/login') {
            return $handler->handle($request);
        }
        /**
         * @var SessionInterface $session
         */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session === null) {
            return new RedirectResponse('/login');
        }
        /** @var User $user */
        $user = $session->get(self::AUTH_SESSION_KEY);
        if ($user === null) {
            return new RedirectResponse('/login');
        }

        $this->template->addDefaultParam(TemplateRendererInterface::TEMPLATE_ALL, 'user', $user);
        return $handler->handle($request->withAttribute(self::AUTH_SESSION_KEY, $user));
    }
}
