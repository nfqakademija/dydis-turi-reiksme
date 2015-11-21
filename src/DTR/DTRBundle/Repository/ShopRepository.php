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
}