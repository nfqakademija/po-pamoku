<?php

namespace App\DataFixtures\ORM;

use App\Entity\Category;
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
            $subcategory = $this->createSubcategory($line);
            $manager->persist($subcategory);
        }

        $manager->flush();
    }

    /**
     * @param array $subcategoryData
     * @return Subcategory
     */
    private function createSubcategory(array $subcategoryData): Subcategory
    {
        $subcategory = new Subcategory();
        $category = $this->createCategory($subcategoryData[0]);
        $subcategory
            ->setCategory($category)
            ->setName($subcategoryData[1])
            ;
        $this->addReference($subcategoryData[1], $subcategory);
        return $subcategory;
    }

    /**
     * @param string $categoryName
     * @return Category
     */
    private function createCategory(string $categoryName): Category
    {
        if ($this->hasReference($categoryName)) {
            $category = $this->getReference($categoryName);
        } else {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference($categoryName, $category);
        }

        return $category;
    }
}