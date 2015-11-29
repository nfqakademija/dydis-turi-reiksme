<?php

namespace DTR\DTRBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository
{
    public function findAll() {
        return $this->getEntityManager()
            ->createQuery('SELECT i FROM DTRBundle:Item i')
            ->getResult();
    }

    public function findByMemberId($member) {
        return $this->getEntityManager()
            ->createQuery('SELECT i FROM DTRBundle:Item i WHERE i = ?1')
            ->setParameter(1, $member->getId())
            ->getResult();
    }
}
