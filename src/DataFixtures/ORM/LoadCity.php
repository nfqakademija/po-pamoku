<?php
	/**
	 * Created by PhpStorm.
	 * User: ruta
	 * Date: 18.4.7
	 * Time: 11.12
	 */
	
	namespace App\DataFixtures\ORM;
	
	use App\Entity\City;
	use Doctrine\Bundle\FixturesBundle\Fixture;
	use Doctrine\Common\Persistence\ObjectManager;
	
	class LoadCity extends Fixture
	{
		public const CITY_NAMES = [
			'Vilnius',
			'Kaunas',
			'Šiauliai',
			'Klaipėda',
			'Prienai',
			'Tauragė',
			'Panevėžys',
		];
		
		public function load(ObjectManager $manager)
		{
			foreach (self::CITY_NAMES as $cityName) {
				$city = new City();
				$city->setName($cityName);
				$this->addReference($cityName, $city);
				$manager->persist($city);
			}
			
			$manager->flush();
		}
	}