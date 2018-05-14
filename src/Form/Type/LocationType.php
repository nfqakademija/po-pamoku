<?php

namespace App\Form\Type;

use App\Entity\Location;
use App\Utils\Utils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('city', CityType::class, ['label' => false])
            ->add('street', TextType::class)
            ->add('house', TextType::class)
            ->add('apartment', TextType::class,
                ['required' => false])
            ->add('lng', HiddenType::class)
            ->add('lat', HiddenType::class)
            ->add('postcode', HiddenType::class);
    
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
    
                $location = $event->getData();
                
                $address = $location["street"] . ' ' . $location['house'] . ', ' . $location["city"]["name"];
                $data = Utils::fetchLocationByAddress($address);
                $location['lng'] = $data['lng'];
                $location['lat'] = $data['lat'];
                $location['postcode'] = $data['postcode'];
                $location['street'] = $data['street'];
                $location['city'] = $data['city'];
                $event->setData($location);

            });
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
    
}