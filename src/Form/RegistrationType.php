<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.14
 * Time: 19.18
 */

namespace App\Form;


use App\Entity\Activity;
use App\Entity\User;
use App\Form\Type\ActivityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Exception\LockedException;

class RegistrationType extends AbstractType
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class
            ])
            ->add('phoneNumber');

        $request = $this->requestStack->getCurrentRequest();
        $role = $request->get('role');
        if (!$role) {
            throw new \LogicException('Registration form cannot be used without passing a role!');
        }

        if ($role !== 'owner' && $role !== 'user') {
            throw new \LogicException('Invalid role passed to registration form.');
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($role) {
                $form = $event->getForm();


                if ($role == 'owner') {
                    $form->add('role', HiddenType::class, [
                        'data' => 'ROLE_USER_OWNER',
                    ]);
                    $form->add('activity', ActivityType::class);
                }

                if ($role == 'user') {
                    $form->add('role', HiddenType::class, [
                        'data' => 'ROLE_USER',
                    ]);
                }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}