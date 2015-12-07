<?php

namespace DTR\DTRBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DTR\DTRBundle\Entity\Shop;
use DTR\DTRBundle\Entity\Product;

class LoadShopData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $shop = new Shop();
        $shop->setName('Čili Pica');
        $shop->setImageLocation('images/shops/cili_pica.png');
        $manager->persist($shop);

        $product = new Product();
        $product->setName('STUDENTŲ pica');
        $product->setPrice(9,78);
        $product->setImageLocation('images/products/STUDENTU.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('KAPRIO pica');
        $product->setPrice(9,75);
        $product->setImageLocation('images/products/KAPRIO.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('KAIMIŠKA pica');
        $product->setPrice(8,65);
        $product->setImageLocation('images/products/KAIMISKA.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

//-------------------------------------------------------------------------

        $shop = new Shop();
        $shop->setName('Can can');
        $shop->setImageLocation('images/shops/can_can.jpg');
        $manager->persist($shop);

        $product = new Product();
        $product->setName('GRAIKIŠKA pica');
        $product->setPrice(6.29);
        $product->setImageLocation('images/products/GRAIKISKA.jpg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('GRYBŲ pica');
        $product->setPrice(6.29);
        $product->setImageLocation('images/products/GRYBU.jpg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('STAMBULO pica');
        $product->setPrice(7.59);
        $product->setImageLocation('images/products/STAMBULO.jpg');
        $product->setShop($shop);
        $manager->persist($product);

//-------------------------------------------------------------------------

        $shop = new Shop();
        $shop->setName('Alikebabai');
        $shop->setImageLocation('images/shops/alikebabai.jpg');
        $manager->persist($shop);

        $product = new Product();
        $product->setName('Kebabas su kiauliena');
        $product->setPrice(2,80);
        $product->setImageLocation('images/products/kebabas_kiauliena.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Kebabas su vištiena');
        $product->setPrice(3,45);
        $product->setImageLocation('images/products/kebabas_vistiena.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Kebabas pitoje su kiauliena');
        $product->setPrice(2,80);
        $product->setImageLocation('images/products/kebabas_pitoje.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

//-------------------------------------------------------------------------

        $shop = new Shop();
        $shop->setName('Jammi kebabai');
        $shop->setImageLocation('images/shops/jammi_kebabai.png');
        $manager->persist($shop);

        $product = new Product();
        $product->setName('Kebabas lėkštėje');
        $product->setPrice(4,10);
        $product->setImageLocation('images/products/kebabas_leksteje.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Buritas');
        $product->setPrice(4,00);
        $product->setImageLocation('images/products/buritas.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Kebabo kompleksas');
        $product->setPrice(5,45);
        $product->setImageLocation('images/products/kebabo_kompleksas.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

//-------------------------------------------------------------------------

        $shop = new Shop();
        $shop->setName('Kinų vyšnia');
        $shop->setImageLocation('images/shops/kinu_vysnia.jpg');
        $manager->persist($shop);

        $product = new Product();
        $product->setName('Hotategai');
        $product->setPrice(2,10);
        $product->setImageLocation('images/products/hotategai.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Love sushi');
        $product->setPrice(2,90);
        $product->setImageLocation('images/products/love_sushi.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Ikura sushi');
        $product->setPrice(3,60);
        $product->setImageLocation('images/products/ikura_sushi.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

//-------------------------------------------------------------------------

        $shop = new Shop();
        $shop->setName('Panda kinija');
        $shop->setImageLocation('images/shops/panda_kinija.jpg');
        $manager->persist($shop);

        $product = new Product();
        $product->setName('Ebi');
        $product->setPrice(2,10);
        $product->setImageLocation('images/products/ebi.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Unagi');
        $product->setPrice(2,70);
        $product->setImageLocation('images/products/unagi.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Maguro');
        $product->setPrice(2,40);
        $product->setImageLocation('images/products/maguro.jpeg');
        $product->setShop($shop);
        $manager->persist($product);

//-------------------------------------------------------------------------

        $manager->flush();
    }
}


