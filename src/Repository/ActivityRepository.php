<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Activity::class);
    }


    public function fetchValues()
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id','a.name','a.description','a.price_from','a.price_to')
            ->select('a.age_from', 'a.age_to','a.path_to_logo', 'c.name', 'l.street')
            ->select( 'l.house', 'l.apartment','l.postcode','ca.name', 'sc.name')
            ->leftJoin('App\Entity\Location', 'l', 'WITH', 'a.location_id = l.id')
            ->leftJoin('App\Entity\City', 'c', 'WITH', 'l.city_id = c.id')
            ->leftJoin('App\Entity\Subcategory', 'sc', 'WITH', 'a.subcategory_id = sc.id')
            ->leftJoin('App\Entity\Category', 'ca', 'WITH', 'sc.category_id = ca.id')
            ->getQuery();

        return $qb->execute();
    }
    public function fetchValuesById($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id','a.name','a.description','a.price_from','a.price_to')
            ->select('a.age_from', 'a.age_to','a.path_to_logo', 'c.name', 'l.street')
            ->select( 'l.house', 'l.apartment','l.postcode','ca.name', 'sc.name')
            ->leftJoin('App\Entity\Location', 'l', 'WITH', 'a.location_id = l.id')
            ->leftJoin('App\Entity\City', 'c', 'WITH', 'l.city_id = c.id')
            ->leftJoin('App\Entity\Subcategory', 'sc', 'WITH', 'a.subcategory_id = sc.id')
            ->leftJoin('App\Entity\Category', 'ca', 'WITH', 'sc.category_id = ca.id')
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $qb->execute();
    }
    public function fetchTimetablesById($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('tt.time_from','tt.time_to','w.name')
            ->leftJoin('App\Entity\Timetable', 'tt', 'WITH', 'a.timetables = tt.id')
            ->leftJoin('App\Entity\Weekday', 'w', 'WITH', 'tt.weekday_id = w.id')
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $qb->execute();
    }

}