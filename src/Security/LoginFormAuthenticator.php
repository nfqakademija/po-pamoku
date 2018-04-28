<?php
	
	namespace App\Security;
	
	use App\Form\LoginType;
	use Symfony\Component\Form\FormFactoryInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Request;
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
		
		public function __construct(
			RouterInterface $router,
			UserPasswordEncoderInterface $encoder,
			FormFactoryInterface $factory
		) {
			$this->router = $router;
			$this->encoder = $encoder;
			$this->factory = $factory;
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
			$targetPath = $this->getTargetPath($request->getSession(), $providerKey);
			
			if (!$targetPath) {
				$targetPath = $this->router->generate('activity_list');
			}
			
			return new RedirectResponse($targetPath);
		}
		
		protected function getLoginUrl()
		{
			return $this->router->generate('login');
		}
		
	}