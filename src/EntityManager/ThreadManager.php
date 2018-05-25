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
            ->innerJoin('tm.participant', 'p')
            ->addselect('p.name')
            ->where(
                $qb->expr()->in('tm.thread', $this->getSubqueryForThreadList()->getDQL())
            )
            ->setParameter('participant_id', $participant->getId())
            ->andWhere('p.id != :user_id')
            ->setParameter('user_id', $participant->getId())
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)
            ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')
            ->andWhere('tm.lastMessageDate IS NOT NULL')
            ->orderBy('tm.lastMessageDate', 'DESC')
            ;
    }

    public function getParticipantSentThreadsQueryBuilder(ParticipantInterface $participant)
    {
        $qb = $this->repository->createQueryBuilder('t');

        return $qb
            ->select('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')
            ->addSelect('p.name')
            ->where(
                $qb->expr()->in('tm.thread', $this->getSubqueryForThreadList()->getDQL())
            )
            ->setParameter('participant_id', $participant->getId())
            ->andWhere('p.id != :user_id')
            ->setParameter('user_id', $participant->getId())
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)
            ->orderBy('tm.lastMessageDate', 'DESC')
            ;
    }

    public function getParticipantDeletedThreadsQueryBuilder(ParticipantInterface $participant)
    {
        $qb = $this->repository->createQueryBuilder('t');

        return $qb
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')
            ->addSelect('p.name')
            ->where(
                $qb->expr()->in('tm.thread', $this->getSubqueryForThreadList()->getDQL())
            )
            ->setParameter('participant_id', $participant->getId())
            ->andWhere('p.id != :user_id')
            ->setParameter('user_id', $participant->getId())
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', true, \PDO::PARAM_BOOL)
            ->orderBy('tm.lastMessageDate', 'DESC')
            ;
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