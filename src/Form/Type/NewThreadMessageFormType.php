<?php


namespace App\Form\Type;


use App\Repository\ActivityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewThreadMessageFormType extends AbstractType
{
    private $requestStack;
    private $ar;
    private $session;

    public function __construct(
        RequestStack $requestStack,
        ActivityRepository $ar,
        SessionInterface $session
    ) {
        $this->requestStack = $requestStack;
        $this->ar = $ar;
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient', UsernameFormType::class, [
                'label' => 'recipient',
                'translation_domain' => 'FOSMessageBundle'
            ])
            ->add('subject', TextType::class, [
                'label' => 'subject',
                'translation_domain' => 'FOSMessageBundle'
            ])
            ->add('body', TextareaType::class, [
                'label' => 'body',
                'translation_domain' => 'FOSMessageBundle'
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $message = $event->getData();
                $request = $this->requestStack->getCurrentRequest();
                $previousPath = $this->getPreviousPath($request);

                if (strpos($previousPath, 'activity') !== false) {
                    $id = $this->getIdFromReferer($previousPath);
                    $activity = $this->ar->find($id);
                    $user = $activity->getUser();
                    $message->setRecipient($user);
                    $event->setData($message);
                }
            });
    }

    private function getPreviousPath(Request $request)
    {
        $pathFromReferer = $request->headers->get('referer');
        $pathFromSession = $this->session->get('messageReceiver');

        if (strpos($pathFromReferer, 'activity')) {
            return $pathFromReferer;
        } elseif (strpos($pathFromSession, 'activity')) {
            $this->session->remove('messageReceiver');
            return $pathFromSession;
        }

        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'intention' => 'message'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'new_thread_message';
    }

    /**
     * @param string $referer
     * @return string
     */
    private function getIdFromReferer(string $referer): string
    {
        $pathComponents = explode('/', $referer);

        return $pathComponents[sizeof($pathComponents) - 1];
    }
}