<?php
/**
 * Created by PhpStorm.
 * User: ruta
 * Date: 18.4.7
 * Time: 11.59
 */

namespace App\DataFixtures\ORM;


use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSubcategory extends Fixture implements DependentFixtureInterface
{
    public const SUBCATEGORIES = [
        'Sportas' => [
            'Plaukimas',
            'Krepšinis',
            'Futbolas'
        ],
        'Menai' => [
            'Tapyba'
        ],
        'Kalbos' => [
            'Anglų',
            'Rusų',
            'Lenkų'
        ],
        'Technologijos' => [
            'Robotika'
        ]
    ];

    public const SUB_NAMES = [
        'Plaukimas', 'Krepšinis', 'Futbolas', 'Tapyba', 'Anglų', 'Rusų', 'Lenkų', 'Robotika'
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::SUBCATEGORIES as $categoryName=>$subcategoryNames) {
            foreach ($subcategoryNames as $subcategoryName) {
                $subcategory = new Subcategory();
                $subcategory->setName($subcategoryName)
                ->setCategory($this->getReference($categoryName));
                $this->addReference($subcategoryName, $subcategory);
                $manager->persist($subcategory);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadCategory::class,
        );
    }

}