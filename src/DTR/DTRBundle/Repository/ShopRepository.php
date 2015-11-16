<?php

namespace DTR\DTRBundle\Repository;

use Doctrine\ORM\EntityRepository;
use DTR\DTRBundle\Entity\Shop;

class ShopRepository extends EntityRepository
{
    /**
     * @param $name
     * @return Shop
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByName($name)
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT sh FROM DTRBundle:Shop sh WHERE sh.name = :name')
            ->setParameter('name', $name)
            ->getOneOrNullResult();
    }
}