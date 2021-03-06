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

    public function findByMember($member) {
        return $this->getEntityManager()
            ->createQuery('SELECT i FROM DTRBundle:Item i WHERE i.member = ?1')
            ->setParameter(1, $member)
            ->getResult();
    }

    public function findByProductAndMember($product, $member) {
        return $this->getEntityManager()
        ->createQuery('SELECT i FROM DTRBundle:Item i WHERE i.product = ?1 AND i.member = ?2')
            ->setParameter(1, $product->getId())
            ->setParameter(2, $member->getId())
            ->getOneOrNullResult();
    }
}
