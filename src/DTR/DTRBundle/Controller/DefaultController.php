<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Event;
use DTR\DTRBundle\Entity\Item;
use DTR\DTRBundle\Entity\Member;
use DTR\DTRBundle\Entity\Product;
use DTR\DTRBundle\Entity\Shop;
use DTR\DTRBundle\Form\EventType;
use DTR\DTRBundle\Form\SearchType;
use DTR\DTRBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render(
            'views/default/index.html.twig'
        );
    }

    /**
     * @Route("/event/{hash}/shops", name="_shops_list")
     * @Template()
     */
    public function shopsAction(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();

        $shops = $em->getRepository('DTRBundle:Shop')->findAllShops();
        $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
        $form = $this->createForm(new SearchType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $query = $form->get('searchInput')->getData();
            $shops = $em->getRepository('DTRBundle:Shop')->searchShops($query);
        }

        return $this->render(
            'views/menu/shops.html.twig',
            array(
                'shops' => $shops,
                'event' => $event[0],
                'form' => $form->createView())
        );
    }

    /**
     * @Route("event/{hash}/shops/{shopName}", name="_shop")
     * @Template()
     */
    public function shopAction(Request $request, $shopName, $hash)
    {
        $em = $this->getDoctrine()->getManager();

        $shop = $em->getRepository('DTRBundle:Shop')->findShopId($shopName);

        $products = $em->getRepository('DTRBundle:Product')->findAllShopProducts($shop);
        $user = $this->getUser();
        $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
        $member = $em->getRepository('DTRBundle:Member')->findByEventUser($event[0], $user);
        $items = $em->getRepository('DTRBundle:Item')->findByMember($member);

        $totalCost = 0.0;
        foreach ($items as $item) {
            $productPrice = $item->getProduct()->getPrice() * $item->getQuantity();
            $totalCost += $productPrice;
        }

        $form = $this->createForm(new ProductType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $query = $form->get('searchInput')->getData();
            $products = $em->getRepository('DTRBundle:Product')->searchProducts($query);
        }

        return $this->render(
            'views/menu/products.html.twig',
            array(
                'products' => $products,
                'event' => $event[0],
                'items' => $items,
                'shopName' => $shopName,
                'totalCost' => $totalCost,
                'form' => $form->createView()
             )
        );
    }
}