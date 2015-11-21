<?php

namespace DTR\DTRBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findAllShopProducts($shop)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM DTRBundle:Product p WHERE p.shop = ?1')
            ->setParameter(1, $shop)
            ->getResult();
    }
}