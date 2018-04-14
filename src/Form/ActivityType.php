<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Subcategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('priceFrom')
            ->add('priceTo')
            ->add('ageFrom')
            ->add('ageTo')
            ->add('pathToLogo')
            ->add('subcategory', EntityType::class, [
                'class' => Subcategory::class,
                'placeholder' => 'Pasirinkite subkategoriją'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
            'validation_groups' => ['Default', 'Registration']
        ]);
    }
}
