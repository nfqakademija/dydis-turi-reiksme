<?php

namespace DTR\DTRBundle\Repository;

use Doctrine\ORM\EntityRepository;
use DTR\DTRBundle\Entity\Event;
use DTR\DTRBundle\Entity\User;

class EventRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function findHostedEvents(User $user)
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT e
                           FROM DTRBundle:Event e
                           JOIN e.members m
                           WHERE m.user = :user
                           AND m.is_host = true')
            ->setParameter('user', $user)
            ->getResult();
    }
}