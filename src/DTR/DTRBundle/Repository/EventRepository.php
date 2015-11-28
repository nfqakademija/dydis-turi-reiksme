<?php
/**
 * Created by PhpStorm.
 * User: justas
 * Date: 15.11.26
 * Time: 21.14
 */

namespace DTR\DTRBundle\Repository;

use Doctrine\ORM\EntityRepository;
use DTR\DTRBundle\Entity\Event;

class EventRepository extends EntityRepository
{
    /**
     * @param $hash
     * @return Event
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
//    public function findByHash($hash)
//    {
//        $this
//            ->getEntityManager()
//            ->createQuery('SELECT e FROM DTRBundle:Event e WHERE e.hash = :hash')
//            ->setParameter('hash', $hash)
//            ->getOneOrNullResult();
//    }
}