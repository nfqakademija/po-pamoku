<?php


namespace App\EntityManager;

use Doctrine\ORM\QueryBuilder;
use FOS\MessageBundle\EntityManager\ThreadManager as BaseManager;
use FOS\MessageBundle\Model\ParticipantInterface;

class ThreadManager extends BaseManager
{
    public function getParticipantInboxThreadsQueryBuilder(ParticipantInterface $participant)
    {
        $qb =  $this->repository->createQueryBuilder('t');

        return $qb
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('t.metadata', 'tm2')
            ->innerJoin('tm.participant', 'p')
            ->innerJoin('tm2.participant', 'p2')
            ->addSelect('p2.name')
            ->where(
                $qb->expr()->in('tm.thread', $this->getSubqueryForThreadList()->getDQL())
            )
            ->setParameter('participant_id', $participant->getId())
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())
            ->andWhere('p.id != p2.id')
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)
            ->andWhere('tm.lastMessageDate IS NOT NULL')
            ->orderBy('tm.lastMessageDate', 'DESC')
            ;
    }

    public function getParticipantSentThreadsQueryBuilder(ParticipantInterface $participant)
    {
        $qb = $this->repository->createQueryBuilder('t');

        return $qb
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('t.metadata', 'tm2')
            ->innerJoin('tm.participant', 'p')
            ->innerJoin('tm2.participant', 'p2')
            ->addSelect('p2.name')
            ->where(
                $qb->expr()->in('tm.thread', $this->getSubqueryForThreadList()->getDQL())
            )
            ->setParameter('participant_id', $participant->getId())
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())
            ->andWhere('p.id != p2.id')
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)
            ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')
            ->orderBy('tm.lastParticipantMessageDate', 'DESC')
            ;
    }

    public function getParticipantDeletedThreadsQueryBuilder(ParticipantInterface $participant)
    {
        $qb = $this->repository->createQueryBuilder('t');

        $qb = $qb
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('t.metadata', 'tm2')
            ->innerJoin('tm.participant', 'p')
            ->innerJoin('tm2.participant', 'p2')
            ->addSelect('p2.name')
            ->where(
                $qb->expr()->in('tm.thread', $this->getSubqueryForThreadList()->getDQL())
            )
            ->setParameter('participant_id', $participant->getId())
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())
            ->andWhere('p.id != p2.id')
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', true, \PDO::PARAM_BOOL)
            ->orderBy('tm.lastMessageDate', 'DESC')
            ;
        return $qb;
    }

    private function getSubqueryForThreadList() : QueryBuilder
    {
        $qb2 = $this->repository->createQueryBuilder('tt')
            ->innerJoin('tt.metadata', 'ttm')
            ->innerJoin('ttm.participant', 'pp')
            ->select('tt.id')
            ->where('pp.id = :participant_id');

        return $qb2;
    }
}