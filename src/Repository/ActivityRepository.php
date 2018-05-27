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
    private $filters = [];
    
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Activity::class);
    }
    
    public function fetchFilteredData($criteria = [], $orderBy = ["name" => "ASC"])
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id as id', 'a.rating as rating', 'a.name as name', 'a.priceFrom as priceFrom', 'a.priceTo as priceTo',
                'a.pathToLogo as pathToLogo', 'a.ageFrom as ageFrom', 'a.ageTo as ageTo', 'c.name as city',
                'l.street as street', 'l.house as house', 'l.apartment as apartment', 'l.postcode as postcode',
                'l.lat as lat', 'l.lng as lng', 'ca.name as category', 'sc.name as subcategory')
            ->leftJoin('a.location', 'l')
            ->leftJoin('l.city', 'c')
            ->leftJoin('a.subcategory', 'sc')
            ->leftJoin('sc.category', 'ca')
            ->join('a.timetables', 'tt')
            ->leftJoin('tt.weekday', 'w');
        
        if (!empty($criteria)) {
            $this->filters = $criteria;
            $qb = $this->filters($qb);
        }
        
        $qb = $qb
            ->orderBy(key($orderBy), array_values($orderBy)[0])
            ->groupBy("a.id")
            ->getQuery();
        
        return $qb->execute();
    }
    
    private function filters($qb)
    {
        if (!empty($this->filters["search"])) {
            $search = "%" . $this->filters["search"] . "%";
            $qb = $qb
                ->orWhere('a.name like :name')
                ->setParameter('name', $search);
        }
        
        if (!empty($this->filters["price"]) && is_numeric($this->filters["price"])) {
            $qb = $qb
                ->andWhere('a.priceFrom <= :price')
                ->andWhere('a.priceTo >= :price')
                ->setParameter('price', $this->filters["price"]);
        }
        
        if (!empty($this->filters["age"]) && is_numeric($this->filters["age"])) {
            $qb = $qb
                ->andWhere('a.ageFrom <= :age')
                ->andWhere('a.ageTo >= :age')
                ->setParameter('age', $this->filters["age"]);
        }
        
        if (!empty($this->filters["time"]) && preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/",
                $this->filters["time"])) {
            $qb = $qb
                ->andWhere('tt.timeFrom <= :time')
                ->andWhere('tt.timeTo >= :time')
                ->setParameter('time', $this->filters["time"]);
        }
        if (!empty($this->filters["weekday"])) {
            $qb = $qb
                ->andWhere('w.id = :weekday')
                ->setParameter('weekday', $this->filters["weekday"]);
        }
        if (!empty($this->filters["category"])) {
            $qb = $qb
                ->andWhere('ca.id = :category')
                ->setParameter('category', $this->filters["category"]);
        }
        if (!empty($this->filters["subcategory"])) {
            $qb = $qb
                ->andWhere('sc.id = :subcategory')
                ->setParameter('subcategory', $this->filters["subcategory"]);
        }
        if (!empty($this->filters["city"])) {
            $qb = $qb
                ->andWhere('c.id = :city')
                ->setParameter('city', $this->filters["city"]);
        }
        if (!empty($this->filters["rating"])) {
            $qb = $qb
                ->andWhere('a.rating <= 5')
                ->andWhere('a.rating >= :rating')
                ->setParameter('rating', $this->filters["rating"]);
        }
        
        if (!empty($this->filters["id"])) {
            $qb = $qb
                ->andWhere('a.id NOT IN (:id)')
                ->setParameter('id', $this->filters["id"]);
        }
        
        return $qb;
    }
    
}