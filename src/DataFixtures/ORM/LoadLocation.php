<?php

namespace App\DataFixtures\ORM;

use App\Entity\City;
use App\Entity\Location;
use App\Utils\Utils;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadLocation extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dataArray = [];
        $handler = fopen('public/data/Addresses.csv', 'r');
        while (($data = fgetcsv($handler, 5000, ';')) !== FALSE) {
            $dataArray[] = $data;
        }
        for ($i = 21; $i <= 70; $i++) {
            $location = new Location();
            $locationData = $dataArray[array_rand($dataArray, 1)];
            $cityName = $locationData[4];
            if ($this->hasReference($cityName)) {
                $location->setCity($this->getReference($cityName));
            } else {
                $city = new City();
                $city->setName($cityName);
                $location->setCity($city);
                $this->addReference($cityName, $city);
            }
            $location
                ->setApartment(rand(1, 84))
                ->setHouse($locationData[2])
                ->setStreet($locationData[1])
            ;

            $address = $locationData[1] . ' ' . $locationData[2] . ', ' . $locationData[4];
            $data = Utils::fetchLocationByAddress($address);
            $location
                ->setLng($data['lng'])
                ->setLat($data['lat'])
                ->setPostcode($data['postcode'])
                ;
            $this->addReference($i, $location);
            $manager->persist($location);
        }
        $manager->flush();
    }



}






