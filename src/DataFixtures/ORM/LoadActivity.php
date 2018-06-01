<?php

namespace App\DataFixtures\ORM;

use App\Entity\Activity;
use App\Entity\Subcategory;
use App\Utils\Utils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadActivity extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $activityDataFile = 'public/data/Activities.csv';
        $data = Utils::getData($activityDataFile);
        foreach ($data as $index=>$line) {
            $activity = $this->createActivity($line, $index);
            $manager->persist($activity);
        }
        $manager->flush();
    }

    /**
     * @param array $activityData
     * @param int $index
     * @return Activity
     */
    private function createActivity(array $activityData, int $index): Activity
    {
        $activity = new Activity();
        $userIndex = $index+1;
        $activity
            ->setAgeFrom(rand(1, 17))
            ->setAgeTo(18)
            ->setDescription("Mūsų teikiamos būrelio paslaugos yra vedamos profesionalių dėstytojų,
             kvalifikuotų bei atestuotų specelistų. Kiekvieno užsiemimo metu mokiniui suteikiamos tiek teorinės,
             tiek praktinės žinios apie vykdomą būrelio užsiemimą. Motyvuodami mokinius lankyti užsiemimą rengiame
             kiekvieno mėsnesio pabaigoje motyvacines dovanas, prizus. Visi ". $activity->getAgeFrom() . " - " .
                $activity->getAgeTo() . " metų mokyniai norintys lankyti užsiemimą gali kreiptis į mus žinute arba atvykus
                pas mus nurodytu adresu.")
            ->setLocation($this->getReference($index+101))
            ->setName($activityData[0])
            ->setPathToLogo("/uploads/" . $activityData[4])
            ->setPriceFrom($activityData[2])
            ->setPriceTo($activityData[3])
            ->setSubcategory($this->getReference($activityData[1]))
            ->addTimetable($this->getReference(rand(1, 20)))
            ->addTimetable($this->getReference(rand(1, 20)))
            ->addTimetable($this->getReference(rand(1, 20)))
            ->setUser($this->getReference("user$userIndex"));
        ;

        return $activity;
    }
    
    public function getDependencies()
    {
        return [
            LoadSubcategory::class,
            LoadLocation::class,
            LoadTimetable::class,
            LoadUser::class
        ];
    }
}