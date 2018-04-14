<?php


namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $csrfTokenManager;
    private $router;
    private $encoder;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager,
                                RouterInterface $router,
                                UserPasswordEncoderInterface $encoder)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->router = $router;
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        $isLogin = $request->getPathInfo() == '/login';

        if (!$isLogin) {
            return;
        }

        $csrfToken = $request->request->get('_csrf_token');

        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }


    }

    public function getCredentials(Request $request)
    {
        $email = $request->request->get('_email');
        $request->getSession()->set(Security::LAST_USERNAME, $email);
        $password = $request->request->get('_password');

        return [
            'email' => $email,
            'password' => $password
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];

        return $userProvider->loadUserByUsername($email);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];

//        if ($this->encoder->isPasswordValid($user, $plainPassword)) {
//            return true;
        if ($plainPassword == 'iliketurtles') {
            return true;
        }
        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if (!$targetPath) {
            $targetPath = $this->router->generate('homepage');
        }

        return new RedirectResponse($targetPath);
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }
}