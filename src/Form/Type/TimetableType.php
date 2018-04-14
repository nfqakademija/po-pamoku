<?php

namespace App\Form\Type;

use App\Entity\Location;
use App\Entity\Timetable;
use App\Entity\Weekday;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class TimetableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('Weekday', EntityType::class, array(
                'label' => "Savitės Diena",
                'class' => Weekday::class,
                'attr' => array('class' => 'form-control'),
            ))
            ->add('timeFrom', TimeType::class, array('label' => "Laikas nuo", 'attr' => array('class' => 'form-control')))
            ->add('timeTo', TimeType::class, array('label' => "Laikas iki", 'attr' => array('class' => 'form-control')))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Timetable::class,
        ));
    }

}