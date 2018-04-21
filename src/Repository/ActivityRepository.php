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

    public function filter($criteria = array("search"=>''), $orderBy = array("id" => "DESC"), $limit = 10, $offset = 0)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id as id', 'a.name as name','a.priceFrom as priceFrom','a.priceTo as priceTo',
                'a.ageFrom as ageFrom','a.ageTo as ageTo', 'c.name as city', 'l.street as street',
                'l.postcode as postcode','ca.name as category', 'sc.name as subcategory', 'tt.timeFrom as timeFrom',
                'tt.timeTo as timeTo','w.name as weekday')
            ->leftJoin('App\Entity\Location', 'l', 'WITH', 'a.location = l.id')
            ->leftJoin('App\Entity\City', 'c', 'WITH', 'l.city = c.id')
            ->leftJoin('App\Entity\Subcategory', 'sc', 'WITH', 'a.subcategory = sc.id')
            ->leftJoin('App\Entity\Category', 'ca', 'WITH', 'sc.category = ca.id')
            ->join('a.timetables', 'tt')
            ->leftJoin('App\Entity\Weekday', 'w', 'WITH', 'tt.weekday = w.id')
            ->orWhere('a.name like :name')
            ->orWhere('c.name like :city')
            ->setParameter('name', "%".$criteria["search"]."%")
            ->setParameter('city', "%".$criteria["search"]."%")
            ->orderBy(key($orderBy), array_values($orderBy)[0])
            ->setFirstResult( $offset )
            ->setMaxResults($limit)
            ->getQuery();
        return $qb->execute();
    }

//    public function filter()
//    {
//        $qb = $this->createQueryBuilder('a')
//            ->select('a.id','a.name','a.description','a.priceFrom','a.priceTo', 'a.ageFrom',
//                'a.ageTo','a.pathToLogo', 'c.name', 'l.street', 'l.house', 'l.apartment','l.postcode',
//                'ca.name', 'sc.name')
//            ->leftJoin('App\Entity\Location', 'l', 'WITH', 'a.location = l.id')
//            ->leftJoin('App\Entity\City', 'c', 'WITH', 'l.city = c.id')
//            ->leftJoin('App\Entity\Subcategory', 'sc', 'WITH', 'a.subcategory = sc.id')
//            ->leftJoin('App\Entity\Category', 'ca', 'WITH', 'sc.category = ca.id')
//            ->getQuery();
//        return $qb->execute();
//    }

}