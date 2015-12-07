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
        $shop->setName('Čili Pica');
        $shop->setImageLocation('images/shops/cili_pica.png');
        $manager->persist($shop);

        $shop = new Shop();
        $shop->setName('Can can');
        $shop->setImageLocation('images/shops/can_can.jpg');
        $manager->persist($shop);

        $shop = new Shop();
        $shop->setName('Alikebabai');
        $shop->setImageLocation('images/shops/alikebabai.jpg');
        $manager->persist($shop);

        $shop = new Shop();
        $shop->setName('Jammi kebabai');
        $shop->setImageLocation('images/shops/jammi_kebabai.png');
        $manager->persist($shop);

        $shop = new Shop();
        $shop->setName('Kinų vyšnia');
        $shop->setImageLocation('images/shops/kinu_vysnia.jpg');
        $manager->persist($shop);

        $shop = new Shop();
        $shop->setName('Panda kinija');
        $shop->setImageLocation('images/shops/panda_kinija.jpg');
        $manager->persist($shop);

        $manager->flush();
    }
}


