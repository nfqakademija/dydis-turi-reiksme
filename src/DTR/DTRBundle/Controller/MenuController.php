<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Product;
use DTR\DTRBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MenuController extends Controller
{
    /**
     * @Route("/menu/new")
     */
    public function newAction()
    {

    }

    public function populateMenu(array $menu, $shop_name)
    {
        $em = $this->getDoctrine()->getManager();
        $shop = new Shop();

        $shop
            ->setName($shop_name)
            ->setImageLocation($menu['logo']);

        $em->persist($shop);

        foreach($menu['products'] as $menu_entry)
        {
            $product = new Product();
            $product
                ->setImageLocation($menu_entry['image'])
                ->setName($menu_entry['title'])
                ->setDescription($menu_entry['description'])
                ->setPrice($menu_entry['price'])
                ->setShop($shop);

            $em->persist($product);
        }

        $em->flush();

        return new Response('Wrote to db.');
    }
}