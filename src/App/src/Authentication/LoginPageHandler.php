<?php
declare(strict_types=1);

namespace App\Authentication;

use CoreComponent\Entity\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Flash\FlashMessageMiddleware;
use Zend\Expressive\Flash\FlashMessagesInterface;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template;

class LoginPageHandler implements RequestHandlerInterface
{
    private $template;

    public function __construct(Template\TemplateRendererInterface $template = null) {
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /**
         * @var FlashMessagesInterface $flashMessages
         * @var SessionInterface $session
         */
        $flashMessages = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $user = $session->get(AuthenticationMiddleware::AUTH_SESSION_KEY);
        if (!empty($user)) {
            return new RedirectResponse('/');
        }
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();
            if (empty($data['username']) || empty($data['password'])) {
                $flashMessages->flash('formError', __('Please provide username and password!'));
                return new RedirectResponse('/login');
            }
            if ($data['username'] === 'admin' && $data['password'] === '123456') {
                $session->set(
                    AuthenticationMiddleware::AUTH_SESSION_KEY,
                    User::createFromArray(['id' => '1', 'name' => 'John Doe', 'role' => ['id' => 0, 'name' => 'Admin']])->toArray()
                );

                return new RedirectResponse('/');
            }
        }
        $data = [];
        $data['formError']   = $flashMessages->getFlash('formError');
        $data['formSuccess'] = $flashMessages->getFlash('formSuccess');
        return new HtmlResponse($this->template->render('app::login-page', $data));
    }
}
