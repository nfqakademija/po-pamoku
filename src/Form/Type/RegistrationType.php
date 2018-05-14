<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.14
 * Time: 19.18
 */

namespace App\Form\Type;

use App\Entity\User;
use App\Form\Type\ActivityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords do not match.',
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat password']
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
            function (FormEvent $event) use ($role) {
                $form = $event->getForm();
                
                if ($role == 'owner') {
                    $form->add('role', HiddenType::class, [
                        'data' => 'ROLE_OWNER',
                    ]);
                    $form->add('activity', ActivityType::class, [
                        'label' => false,
                    ]);
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
            'data_class' => User::class,
            'validation_groups' => ['Default', 'Register'],
        ]);
    }
}