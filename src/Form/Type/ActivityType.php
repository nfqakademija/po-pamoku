<?php

namespace App\Form\Type;

use App\Entity\Activity;
use App\Entity\Subcategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Activity name'
            ])
            ->add('description', TextareaType::class)
            ->add('location', LocationType::class, ['label' => false])
            ->add('priceFrom', NumberType::class, [
                'scale' => 2,
                'rounding_mode' => NumberToLocalizedStringTransformer::ROUND_DOWN,
            ])
            ->add('priceTo', NumberType::class, [
                'scale' => 2,
                'rounding_mode' => NumberToLocalizedStringTransformer::ROUND_DOWN,
            ])
            ->add('ageFrom', NumberType::class,
                ['scale' => 0])
            ->add('ageTo', NumberType::class,
                ['scale' => 0,])
            ->add('pathToLogo', FileType::class, [
                'data_class' => null])
            ->add('Subcategory', EntityType::class, [
                'class' => Subcategory::class,
            ])
            ->add('timetables', CollectionType::class, [
                'entry_type' => TimetableType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => false
                ]
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
