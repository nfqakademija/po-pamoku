<?php
/**
 * Created by PhpStorm.
 * User: ruta
 * Date: 18.4.7
 * Time: 11.33
 */

namespace App\DataFixtures\ORM;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategory extends Fixture
{
    public const CATEGORY_NAMES = [
    'Sportas',
    'Menai',
    'Kalbos',
    'Technologijos'
    ];

    public function load(ObjectManager $manager)
    {


        foreach (self::CATEGORY_NAMES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference($categoryName, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}