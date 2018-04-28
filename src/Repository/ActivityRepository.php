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
    
//    public function countTotal()
//    {
//        $qb = $this->createQueryBuilder('a');
//
//        $qb = $qb->select($qb->expr()->count('a'))
//            ->getQuery();
//        return $qb->getSingleScalarResult();
//    }
    
    public function fetchFilteredData($criteria = [], $orderBy = ["name" => "ASC"])
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.id as id', 'a.name as name', 'a.priceFrom as priceFrom', 'a.priceTo as priceTo',
                'a.ageFrom as ageFrom', 'a.ageTo as ageTo', 'c.name as city', 'l.street as street',
                'l.postcode as postcode', 'ca.name as category', 'sc.name as subcategory',
                'tt.timeFrom as timeFrom', 'tt.timeTo as timeTo', 'w.name as weekday')
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
            $qb = $qb
                ->orWhere('a.name like :name')
                ->orWhere('c.name like :city')
                ->orWhere('l.street like :street')
                ->orWhere('l.postcode like :postcode')
                ->orWhere('ca.name like :category')
                ->orWhere('sc.name like :subcategory')
                ->orWhere('w.name like :weekday')
                ->setParameter('name', "%" . $this->filters["search"] . "%")
                ->setParameter('city', "%" . $this->filters["search"] . "%")
                ->setParameter('street', "%" . $this->filters["search"] . "%")
                ->setParameter('postcode', "%" . $this->filters["search"] . "%")
                ->setParameter('category', "%" . $this->filters["search"] . "%")
                ->setParameter('subcategory', "%" . $this->filters["search"] . "%")
                ->setParameter('weekday', "%" . $this->filters["search"] . "%");
        }
        if (!empty($this->filters["priceFrom"]) || !empty($this->filters["priceTo"])) {
            $priceFrom = isset($this->filters["priceFrom"]) && is_numeric($this->filters["priceFrom"]) ? $this->filters["priceFrom"] : 0;
            $priceTo = isset($this->filters["priceTo"]) && is_numeric($this->filters["priceTo"]) ? $this->filters["priceTo"] : 100;
            $qb = $qb
                ->andWhere('a.priceFrom >= :priceFrom')
                ->andWhere('a.priceTo <= :priceTo')
                ->setParameter('priceFrom', $priceFrom)
                ->setParameter('priceTo', $priceTo);
        }
        if (!empty($this->filters["ageFrom"]) || !empty($this->filters["ageTo"])) {
            $ageFrom = isset($this->filters["ageFrom"]) && is_numeric($this->filters["ageFrom"]) ? $this->filters["ageFrom"] : 0;
            $ageTo = isset($this->filters["ageTo"]) && is_numeric($this->filters["ageTo"]) ? $this->filters["ageTo"] : 100;
            $qb = $qb
                ->andWhere('a.ageFrom >= :priceFrom')
                ->andWhere('a.ageTo <= :ageTo')
                ->setParameter('ageFrom', $ageFrom)
                ->setParameter('ageTo', $ageTo);
        }
        if (!empty($this->filters["timeFrom"]) || !empty($this->filters["timeTo"])) {
            if (isset($this->filters["timeFrom"]) && preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/",
                    $this->filters["timeFrom"])) {
                $timeFrom = $this->filters["timeFrom"];
            } else {
                $timeFrom = date("H:i", mktime(0, 0));
            }
            
            if (isset($this->filters["timeTo"]) && preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/",
                    $this->filters["timeTo"])) {
                $timeTo = $this->filters["timeTo"];
            } else {
                $timeTo = date("H:i", mktime(23, 59));
            }
            $qb = $qb
                ->andWhere('tt.timeFrom >= :timeFrom')
                ->andWhere('tt.timeTo <= :timeTo')
                ->setParameter('timeFrom', $timeFrom)
                ->setParameter('timeTo', $timeTo);
        }
        if (!empty($this->filters["weekday"])) {
            $qb = $qb
                ->andWhere('w.id = :weekday')
                ->setParameter('weekday', $this->filters["weekday"]);
        }
        if (!empty($this->filters["category"])) {
            $qb = $qb
                ->andWhere('c.id = :category')
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
        
        return $qb;
    }
    
}