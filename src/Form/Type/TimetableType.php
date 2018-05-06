<?php

namespace App\Form\Type;

use App\Entity\Timetable;
use App\Entity\Weekday;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TimetableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Weekday', EntityType::class, [
            'label' => "Savaitės Diena",
            'class' => Weekday::class,
            'attr' => ['class' => 'form-control'],
        ])
            ->add('timeFrom', TimeType::class, ['label' => "Laikas nuo", 'attr' => ['class' => 'form-control']])
            ->add('timeTo', TimeType::class, ['label' => "Laikas iki", 'attr' => ['class' => 'form-control']]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Timetable::class,
        ]);
    }
    
}