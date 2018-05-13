<?php

namespace App\DataFixtures\ORM;

use App\Entity\Subcategory;
use App\Utils\Utils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSubcategory extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        $subcategoryDataFile = 'public/data/Subcategories.csv';
        $data = Utils::getData($subcategoryDataFile);
        foreach ($data as $line) {
        }

//        foreach (self::SUBCATEGORIES as $categoryName => $subcategoryNames) {
//            foreach ($subcategoryNames as $subcategoryName) {
//                $subcategory = new Subcategory();
//                $subcategory->setName($subcategoryName)
//                    ->setCategory($this->getReference($categoryName));
//                $this->addReference($subcategoryName, $subcategory);
//                $manager->persist($subcategory);
//            }
//        }
//        $manager->flush();
    }

    private function createSubcategory(array $subcategoryData): Subcategory
    {
        $subcategory = new Subcategory();

    }
    
}