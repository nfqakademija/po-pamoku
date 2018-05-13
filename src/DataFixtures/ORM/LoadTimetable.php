<?php

namespace App\DataFixtures\ORM;

use App\Entity\Timetable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTimetable extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) {
            $timetable = new Timetable();
            
            $start = rand(12, 18);
            $end = $start + 2;
            
            $timetable->setWeekday($this->getReference(LoadWeekday::WEEKDAY_NAMES[array_rand(LoadWeekday::WEEKDAY_NAMES,
                1)]))
                ->setTimeFrom(\DateTime::createFromFormat("H:i", date("H:i", strtotime("$start:00"))))
                ->setTimeTo(\DateTime::createFromFormat("H:i", date("H:i", strtotime("$end:00"))));
            
            $this->addReference($i, $timetable);
            $manager->persist($timetable);
        }
        
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            LoadWeekday::class,
        ];
    }
}