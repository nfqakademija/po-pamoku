<?php

namespace App\Form\Type;

use App\Entity\City;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Form\Type\CityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('city', EntityType::class, array(
                'label' => "Miestas",
                'class' => City::class,
                'attr' => array('class' => 'form-control'),
            ))
            ->add('street', TextType::class, array('label' => "Gatvė",'attr' => array('class' => 'form-control')) )
            ->add('house', TextType::class, array('label' => "Namas",'attr' => array('class' => 'form-control')))
            ->add('apartment', TextType::class, array('label' => "Butas",'required' => false,'attr' => array('class' => 'form-control')))
            ->add('postcode', TextType::class, array('label' => "Pašto kodas",'attr' => array('class' => 'form-control')));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Location::class,
        ));
    }

}