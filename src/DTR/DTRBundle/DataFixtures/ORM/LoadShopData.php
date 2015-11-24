<?php

namespace DTR\DTRBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DTR\DTRBundle\Entity\Shop;

class LoadShopData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $shop = new Shop();
        $shop->setName('Cili Pica TEST');
        $shop->setImageLocation('images/cili_pica.png');

        $manager->persist($shop);
        $manager->flush();
    }
}


