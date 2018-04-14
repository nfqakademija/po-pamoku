<?php


namespace App\Listeners;


use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class PasswordListener implements EventSubscriber
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passworEndocer = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity  = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encoded);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        if (!$entity->getPlainPasswoes()) {
            return;
        }

        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }


    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }
}