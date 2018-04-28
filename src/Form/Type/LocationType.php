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
			'attr' => ['class' => 'form-control'],
		])
			->add('street', TextType::class, ['label' => "Gatvė", 'attr' => ['class' => 'form-control']])
			->add('house', TextType::class, ['label' => "Namo nr.", 'attr' => ['class' => 'form-control']])
			->add('apartment', TextType::class,
				['label' => "Buto nr.", 'required' => false, 'attr' => ['class' => 'form-control']])
			->add('postcode', TextType::class, ['label' => "Pašto kodas", 'attr' => ['class' => 'form-control']]);
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Location::class,
		]);
	}
	
}