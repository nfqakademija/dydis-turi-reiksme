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
}