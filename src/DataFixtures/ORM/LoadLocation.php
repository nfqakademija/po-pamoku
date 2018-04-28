<?php
/**
 * Created by PhpStorm.
 * User: ruta
 * Date: 18.4.7
 * Time: 12.27
 */

namespace App\DataFixtures\ORM;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadLocation extends Fixture implements DependentFixtureInterface
{
	public const STREET_NAMES = [
		'Lietaus',
		'Miško',
		'Debesų',
		'Upės',
		'Ežerų',
		'Sodų',
		'Pievos',
	];
	
	public function load(ObjectManager $manager)
	{
		for ($i = 21; $i <= 70; $i++) {
			$location = new Location();
			$location->setApartment(rand(1, 84))
				->setHouse(rand(1, 347))
				->setCity($this->getReference(LoadCity::CITY_NAMES[array_rand(LoadCity::CITY_NAMES, 1)]))
				->setStreet(self::STREET_NAMES[array_rand(self::STREET_NAMES, 1)])
				->setPostcode(sprintf('%05d', rand(0, 99999)));
			
			$this->addReference($i, $location);
			$manager->persist($location);
		}
		
		$manager->flush();
	}
	
	public function getDependencies()
	{
		return [
			LoadCity::class,
		];
	}
	
}