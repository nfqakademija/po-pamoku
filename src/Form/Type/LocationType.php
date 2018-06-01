<?php

namespace App\Form\Type;

use App\Entity\Location;
use App\Utils\Utils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LocationType extends AbstractType
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


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
                $location['city']['name'] = $data['city'];
                $event->setData($location);

            });


        $request = $this->requestStack->getCurrentRequest();
        $path = $request->getPathInfo();

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($path) {
                    $form = $event->getForm();

                    if (strpos($path, 'register') !== false) {
                        $form->add('register', SubmitType::class, [
                            'attr' => [
                                'formnovalidate'=>'formnovalidate'
                            ]
                        ]);
                        $form->add('back', SubmitType::class, [
                            'validation_groups' => false,
                            'attr' => [
                                'formnovalidate'=>'formnovalidate'
                            ]
                        ]);
                    }
                });
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
    
}