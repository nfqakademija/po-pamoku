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
            ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Nam dictum erat lorem, ac elementum nunc molestie vitae.
            Aliquam purus felis, blandit non maximus quis, lacinia ut nibh. Mauris vel maximus est. 
            Etiam congue mauris nec ligula pellentesque sagittis. 
            Duis elementum rutrum orci, ut sollicitudin eros commodo sed. 
            Donec et pulvinar est. Integer imperdiet non arcu vel feugiat. 
            Mauris vehicula justo at diam pharetra, in egestas ex congue. 
            Donec lobortis cursus ipsum, at pellentesque neque pretium eget. 
            Ut dignissim metus ut nisi venenatis, quis porttitor magna ornare. 
            Pellentesque mollis erat quis orci ullamcorper, sed volutpat lorem ultrices. 
            Nunc vehicula, nibh quis imperdiet iaculis, ante lectus rhoncus lectus, eu ultricies ligula lacus ac purus.")
            ->setLocation($this->getReference($index+101))
            ->setName($activityData[0])
            ->setPathToLogo('')
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