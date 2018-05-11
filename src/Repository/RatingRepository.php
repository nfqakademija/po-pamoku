<?php

namespace App\Repository;


use App\Entity\Activity;
use Doctrine\ORM\EntityRepository;

class RatingRepository extends EntityRepository
{
    public function countRatingsByActivity(Activity $activity)
    {
        $query = $this->createQueryBuilder('r')
        ->select("avg(r.rating) as avgRating, count(r.id) as countRating")
        ->where('r.activity = :activity')
        ->setParameter('activity', $activity)
        ->getQuery();

        return $query->execute()[0];
    }


}