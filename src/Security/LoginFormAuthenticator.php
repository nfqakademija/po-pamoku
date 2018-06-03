<?php

namespace App\Security;

use App\Form\Type\LoginType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    
    private $router;
    private $encoder;
    private $factory;
    private $session;
    
    public function __construct(
        RouterInterface $router,
        UserPasswordEncoderInterface $encoder,
        FormFactoryInterface $factory,
        SessionInterface $session
    ) {
        $this->router = $router;
        $this->encoder = $encoder;
        $this->factory = $factory;
        $this->session = $session;
    }
    
    public function supports(Request $request)
    {
        return $request->getPathInfo() == '/login' && $request->isMethod('POST');
    }
    
    public function getCredentials(Request $request)
    {
        $form = $this->factory->create(LoginType::class);
        $form->handleRequest($request);
        $data = $form->getData();
        
        return [
            'email' => $data['_username'],
            'password' => $data['_password'],
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
        
        if ($this->encoder->isPasswordValid($user, $plainPassword)) {
            return true;
        }
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $path = $this->redirectAfterRegistration($request, $token, $providerKey);
        $loginReferer = $this->session->get('loginReferer');

        if (!$path) {
            $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
            if (!$targetPath || strpos($targetPath, 'login') || strpos($targetPath, 'register')) {
                if (strpos($loginReferer, 'activity')) {
                    $path = $loginReferer;
                } else {
                    $path = $this->router->generate('activity_list');
                }
            } else {
                $path = $targetPath;
            }
        }

        $this->session->remove('loginReferer');
        return new RedirectResponse($path);
    }
    
    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    private function redirectAfterRegistration(Request $request, TokenInterface $token, $providerKey)
    {
        $session = $request->getSession();
        $user = $token->getUser();
        $role = $user->getRole();
        $path = $session->get('redirect');
        $lastPath = $request->headers->get('referer');
        if (!($role && $path && strpos($lastPath, 'register')) ) {
            return null;
        }

        switch ($role) {
            case 'ROLE_OWNER':
                $activity = $user->getActivity()->getId();
                $path = $this->router->generate('activity_show', ['id' => $activity]);
                break;
            case 'ROLE_USER':
                if (strpos($path, 'login') || strpos($path, 'register')) {
                    return null;
                }
                break;
            default:
                return null;
        }

        $session->clear();
        return $path;
    }
}