<?php

namespace App\Form\Type;

use App\Entity\Activity;
use App\Entity\Location;
use App\Entity\Subcategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Type\LocationType;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => "Būrelio Pavadinimas", 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('label' => "Būrelio aprašymas", 'attr' => array('class' => 'form-control')))
            ->add('location', LocationType::class, array())
            ->add('priceFrom', TextType::class, array('label' => "Kaina nuo", 'attr' => array('class' => 'form-control')))
            ->add('priceTo', TextType::class, array('label' => "Kaina iki", 'attr' => array('class' => 'form-control')))
            ->add('ageFrom', TextType::class, array('label' => "Amžius nuo", 'attr' => array('class' => 'form-control')))
            ->add('ageTo', TextType::class, array('label' => "Amžius iki", 'attr' => array('class' => 'form-control')))
            ->add('pathToLogo', FileType::class, array('label' => 'Paveikslėlis', "data_class" => null))
            ->add('Subcategory', EntityType::class, array(
                'label' => "Būrelio tipas",
                'class' => Subcategory::class,
                'attr' => array('class' => 'form-control'),
            ))
            ->add('timetables', CollectionType::class, array("entry_type" => TimetableType::class , "label" => "Tvarkaraštis"))
            ->add('save', SubmitType::class, array(
                'label' => 'Išsaugoti',
                'attr' => array('class' => 'btn btn-primary mt-3')
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
