<?php

namespace App\Listeners;


use App\Entity\Rating;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class RatingListener implements EventSubscriber
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Rating) {
            return;
        }

        $em = $args->getEntityManager();
        $repo = $em->getRepository(Rating::class);

        $activity = $entity->getActivity();
        $ratings = $repo->countRatingsByActivity($activity);
        $activity->setRatingCount($ratings['countRating']);
        $activity->setRating($ratings['avgRating']);
        $meta = $em->getClassMetadata(get_class($activity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $activity);
        $em->persist($activity);
        $em->flush();
    }

    public function postUpdate(LifecycleEventArgs $args) {
        $this->postPersist($args);
    }

    public function getSubscribedEvents()
    {
        return ['postPersist', 'postUpdate'];
    }

}