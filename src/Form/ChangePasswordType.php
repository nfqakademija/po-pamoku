<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.28
 * Time: 12.19
 */

namespace App\Form;


use App\Form\Model\ChangePasswordModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Dabartinis slaptažodis',
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Naujas slaptažodis'],
                'second_options' => ['label' => 'Pakartoti slaptažodį']
            ]);
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ChangePasswordModel::class,
        ));
    }
}