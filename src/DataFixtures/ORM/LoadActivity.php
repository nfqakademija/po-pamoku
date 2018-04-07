<?php
/**
 * Created by PhpStorm.
 * User: ruta
 * Date: 18.4.7
 * Time: 13.33
 */

namespace App\DataFixtures\ORM;


use App\Entity\Activity;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadActivity extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 50; $i++) {
            $activity = new Activity();
            $activity->setName("Būrelis " . $i);
            $activity->setAgeFrom(rand(1, 17));
            $activity->setAgeTo(18);
            $activity->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam dictum erat lorem, ac elementum nunc molestie vitae. 
            Aliquam purus felis, blandit non maximus quis, lacinia ut nibh. Mauris vel maximus est. Etiam congue mauris nec ligula pellentesque sagittis. 
            Duis elementum rutrum orci, ut sollicitudin eros commodo sed. Donec et pulvinar est. Integer imperdiet non arcu vel feugiat. 
            Mauris vehicula justo at diam pharetra, in egestas ex congue. Donec lobortis cursus ipsum, at pellentesque neque pretium eget. 
            Ut dignissim metus ut nisi venenatis, quis porttitor magna ornare. Pellentesque mollis erat 
            quis orci ullamcorper, sed volutpat lorem ultrices. Nunc vehicula, nibh quis imperdiet iaculis, ante lectus rhoncus lectus, eu ultricies ligula lacus ac purus. ");
            $activity->setPathToLogo('/');
            $activity->setSubcategory($this->getReference(LoadSubcategory::SUB_NAMES[array_rand(LoadSubcategory::SUB_NAMES, 1)]));
            $activity->setLocation($this->getReference(rand(21, 70)));
            $activity->addTimetable($this->getReference(rand(1, 20)));
            $activity->addTimetable($this->getReference(rand(1, 20)));
            $activity->addTimetable($this->getReference(rand(1, 20)));
            $activity->setPriceFrom(10);
            $activity->setPriceTo(20);

            $manager->persist($activity);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadSubcategory::class,
            LoadLocation::class,
            LoadTimetable::class,
        );
    }
}