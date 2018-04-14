<?php
/**
 * Created by PhpStorm.
 * User: ruta
 * Date: 18.4.7
 * Time: 11.27
 */

namespace App\DataFixtures\ORM;


use App\Entity\Weekday;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadWeekday extends Fixture
{
    public const WEEKDAY_NAMES = [
    'Pirmadienis',
    'Antradienis',
    'Trečiadienis',
    'Ketvirtadienis',
    'Penktadienis',
    'Šeštadienis',
    'Sekmadienis'
    ];


    public function load(ObjectManager $manager)
    {


        foreach (self::WEEKDAY_NAMES as $weekdayName) {
            $weekday = new Weekday();
            $weekday->setName($weekdayName);
            $this->addReference($weekdayName, $weekday);
            $manager->persist($weekday);
        }

        $manager->flush();
    }
}