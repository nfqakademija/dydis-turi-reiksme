<?php

namespace DTR\DTRBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ShopRepository extends EntityRepository
{
    public function findAllShops()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM DTRBundle:Shop s')
            ->getResult();
    }

    public function findShopId($shop_name)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM DTRBundle:Shop s WHERE s.slug = ?1')
            ->setParameter(1, $shop_name)
            ->getResult();
    }

    public function searchShops($query)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT s FROM DTRBundle:Shop s WHERE s.name = ?1')
            ->setParameter(1, $query)
            ->getResult();
    }
}