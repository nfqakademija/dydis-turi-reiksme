<?php

namespace DTR\DTRBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function findByHash($hash)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM DTRBundle:Event e WHERE e.hash = ?1')
            ->setParameter(1, $hash)
            ->getResult();
    }

    public function findEventWithMembers($hash)
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT e, m FROM DTRBundle:Event e
                           JOIN e.members m
                           WHERE e.hash = :hash')
            ->setParameter('hash', $hash)
            ->getOneOrNullResult();
    }
}