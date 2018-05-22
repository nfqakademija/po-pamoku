<?php


namespace App\Form\Type;


use App\Repository\ActivityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewThreadMessageFormType extends AbstractType
{
    private $requestStack;
    private $ar;

    public function __construct(RequestStack $requestStack, ActivityRepository $ar)
    {
        $this->requestStack = $requestStack;
        $this->ar = $ar;
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
                $previousPath = $request->headers->get('referer');

                if (strpos($previousPath, 'activity') !== false) {
                    $id = $this->getIdFromReferer($previousPath);
                    $activity = $this->ar->find($id);
                    $user = $activity->getUser();
                    $message->setRecipient($user);
                    $event->setData($message);
                }
            });
    }

    private function getIdFromReferer(string $referer): string
    {
        $pathComponents = explode('/', $referer);

        return $pathComponents[sizeof($pathComponents) - 1];
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
}