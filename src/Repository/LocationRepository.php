<?php
/**
 * Created by PhpStorm.
 * User: juste
 * Date: 18.4.24
 * Time: 12.31
 */

namespace App\Repository;

use App\Entity\Location;
use Doctrine\ORM\EntityRepository;

class LocationRepository extends EntityRepository
{
    public function findLocationByLocation(Location $location)
    {
        return $this->findOneBy([
            'city' => $location->getCity(),
            'street' => $location->getStreet(),
            'house' => $location->getHouse(),
            'apartment' => $location->getApartment(),
            'postcode' => $location->getPostcode(),
        ]);
    }
    
}