<?php

namespace App\Repository;

use App\Entity\Club;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Club|null find($id, $lockMode = null, $lockVersion = null)
 * @method Club|null findOneBy(array $criteria, array $orderBy = null)
 * @method Club[]    findAll()
 * @method Club[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Club::class);
    }


    public function findBySomething($value)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c','a')
            ->leftJoin('App\Entity\Activity', 'a', 'WITH', 'c.activity_id = a.id')
            ->andWhere('c.id = :id')
            ->setParameter('id', $value)
            ->getQuery();

        return $qb->execute();
    }

}