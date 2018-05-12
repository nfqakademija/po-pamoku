<?php

namespace App\Repository;


use App\Entity\Activity;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function findAllPastDay(User $user, $id) {
        $query = $this->createQueryBuilder('c')
            ->where('c.createdAt >= :from')
            ->setParameter('from', new \DateTime('-1 day'))
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->andWhere('c.activity = :activity')
            ->setParameter('activity', $id)
            ->getQuery();

        return $query->execute();
    }


    public function countCommentsByActivity(Activity $activity)
    {
        $query = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.activity = :activity')
            ->setParameter('activity', $activity)
            ->getQuery()
            ->getSingleScalarResult();

        return $query;
    }

    public function findAllCommentsByUserId($id)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $id)
            ->leftJoin('c.activity', 'a')
            ->getQuery();

        return $query->execute();
    }
}