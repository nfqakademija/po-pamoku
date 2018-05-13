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
        $locationDataFile = 'public/data/Addresses.csv';
        $data = Utils::getData($locationDataFile);
        for ($i = 21; $i <= 70; $i++) {
            $location = $this->createLocation($data);
            $this->addReference($i, $location);
            $manager->persist($location);
        }

        $manager->flush();
    }

    /**
     * @param array $data
     * @return Location
     */
    private function createLocation(array $data): Location
    {
        $location = new Location();
        $locationData = $data[array_rand($data, 1)];
        $cityName = $locationData[4];
        $city = $this->createCity($cityName);
        $location
            ->setApartment(rand(1, 84))
            ->setCity($city)
            ->setHouse($locationData[2])
            ->setLat($locationData[6])
            ->setLng($locationData[7])
            ->setPostcode($locationData[5])
            ->setStreet($locationData[1]);

            return $location;
    }


    /**
     * @param string $cityName
     * @return City
     */
    private function createCity(string $cityName): City
    {
        if ($this->hasReference($cityName)) {
            $city = $this->getReference($cityName);
        } else {
            $city = new City();
            $city->setName($cityName);
            $this->addReference($cityName, $city);
        }

        return $city;
    }
}






