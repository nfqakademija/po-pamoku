<?php

namespace App\Form\Type;

use App\Entity\City;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('city', EntityType::class, [
            'label' => "Miestas",
            'class' => City::class,
        ])
            ->add('street', TextType::class, ['label' => "Gatvė"])
            ->add('house', TextType::class, ['label' => "Namo nr."])
            ->add('apartment', TextType::class,
                ['label' => "Buto nr.", 'required' => false])
            ->add('postcode', TextType::class, ['label' => "Pašto kodas"]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
    
}