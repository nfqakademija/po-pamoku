<?php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findProfileInformation($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin(Activity::class, 'a', 'WITH', 'a.user = u.id')
            ->select('u.name as name', 'u.surname as surname',
                'u.email as email', 'u.phoneNumber as phone', 'a.id as activity')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        
        return $qb->execute();
    }
    
    public function findAllUsersForAdmin()
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin(Activity::class, 'a', 'WITH', 'a.user = u.id')
            ->select('u.id as id', 'u.name as name', 'u.surname as surname',
                'u.role as roles', 'u.isBlocked as isblocked')
            ->getQuery();
        
        return $qb->execute();
    }
    
    public function findOneUserForAdmin($id)
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin(Activity::class, 'a', 'WITH', 'a.user = u.id')
            ->select('u.id as id', 'u.name as name', 'u.surname as surname',
                'u.role as roles', 'u.isBlocked as isblocked', 'u.phoneNumber as phone', 'u.email as email',
                'a.id as activity')
            ->where('u.id =:id')
            ->setParameter('id', $id)
            ->getQuery();
        
        return $qb->execute();
    }
    
}