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
        $builder->add('name', TextType::class, array('label' => "Būrelio Pavadinimas", 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('label' => "Būrelio aprašymas", 'attr' => array('class' => 'form-control')))
            ->add('location', LocationType::class, array())
            ->add('priceFrom', NumberType::class, array('label' => "Kaina nuo", 'scale' => 2,
                'rounding_mode' => NumberToLocalizedStringTransformer::ROUND_DOWN, 'attr' => array('class' => 'form-control')))
            ->add('priceTo', NumberType::class, array('label' => "Kaina iki", 'scale' => 2,
                'rounding_mode' => NumberToLocalizedStringTransformer::ROUND_DOWN, 'attr' => array('class' => 'form-control')))
            ->add('ageFrom', NumberType::class, array('label' => "Amžius nuo",'scale' => 0, 'attr' => array('class' => 'form-control')))
            ->add('ageTo', NumberType::class, array('label' => "Amžius iki", 'scale' => 0, 'attr' => array('class' => 'form-control')))
            ->add('pathToLogo', FileType::class, array('label' => 'Paveikslėlis', 'required'=> false, "data_class" => null))
            ->add('Subcategory', EntityType::class, array(
                'label' => "Būrelio tipas",
                'class' => Subcategory::class,
                'attr' => array('class' => 'form-control'),
            ))
            ->add('timetables', CollectionType::class, array(
                "entry_type" => TimetableType::class ,
                "label" => "Tvarkaraštis",
                'allow_add'     => true,
                'allow_delete'  => true,
                ))
            ->add('save', SubmitType::class, array(
                'label' => 'Išsaugoti',
                'attr' => array('class' => 'btn btn-dark mt-3')
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Activity::class,
        ));
    }
}
