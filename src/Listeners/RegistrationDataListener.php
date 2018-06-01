<?php

namespace App\Listeners;

use App\Entity\Activity;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\User;
use App\Repository\LocationRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class RegistrationDataListener implements EventSubscriber
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if (!($entity instanceof Activity)) {
            return;
        }

        if ($entity instanceof Activity) {
            $entity = $this->manageActivity($entity, $em->getRepository(Location::class), $em->getRepository(City::class));
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if (!($entity instanceof Activity)) {
            return;
        }

        if ($entity instanceof Activity) {
            $entity = $this->manageActivity($entity, $em->getRepository(Location::class), $em->getRepository(City::class));
        }

        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    private function manageActivity(Activity $activity, $locationRepository, $cityRepository)
    {
        $location = $activity->getLocation();
        $locationFound = $locationRepository->findLocationByLocation($location);
        if ($locationFound) {
            $activity->setLocation($locationFound);
        } else {
            $location = $this->manageLocation($location, $cityRepository);
            $activity->setLocation($location);
        }

        return $activity;
    }

    private function manageLocation(Location $location, $cityRepository)
    {
        $city = $location->getCity();
        $cityFound = $cityRepository->findOneBy(['name' => $city->getName()]);
        if ($cityFound) {
            $location->setCity($cityFound);
        }

        return $location;
    }
}