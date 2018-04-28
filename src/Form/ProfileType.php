<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.28
 * Time: 11.48
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Vardas'
            ])
            ->add('surname', TextType::class, [
                'label' => 'Pavardė'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Elektroninis paštas'
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Telefono nr.'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}