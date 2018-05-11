<?php

namespace App\Listeners;


use App\Entity\Comment;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CommentListener implements EventSubscriber
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateComments($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->updateComments($args);
    }

    public function updateComments (LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Comment) {
            return;
        }

        $em = $args->getEntityManager();
        $repo = $em->getRepository(Comment::class);

        $activity = $entity->getActivity();
        $commentCount = $repo->countCommentsByActivity($activity);

        $activity->setCommentCount($commentCount);

        $em->persist($activity);
        $meta = $em->getClassMetadata(get_class($activity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $activity);
    }

    public function getSubscribedEvents()
    {
        return ['postPersist', 'postRemove'];
    }

}