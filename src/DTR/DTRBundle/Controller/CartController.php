<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Event;
use DTR\DTRBundle\Entity\Item;
use DTR\DTRBundle\Entity\Member;
use DTR\DTRBundle\Entity\Product;
use DTR\DTRBundle\Entity\Shop;
use DTR\DTRBundle\Form\EventType;
use DTR\DTRBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CartController extends Controller
{
    /**
     * @Route("/event/{hash}/shops/{shopName}/add-to-cart/{productId}", name="_add_to_cart")
     * @Template()
     */
    public function addToCartAction($hash, $productId, $shopName)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $product = $em->getRepository('DTRBundle:Product')->find($productId);
        $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
        $member = $em->getRepository('DTRBundle:Member')->findByEventUser($event[0], $user);

        $item = $em->getRepository('DTRBundle:Item')->findByProduct($product);

        if ($item) {
            $item->setQuantity($item->getQuantity()+1);
        } else {
            $item = new Item();
            $item->setProduct($product);
            $item->setMember($member);
            $em->persist($item);
        }

        $em->flush();

        return $this->forward('DTRBundle:Default:shop', array(
            'hash' => $hash,
            'shopName' => $shopName
        ));
    }

    /**
     * @Route("/event/{hash}/remove-from-cart/{itemId}", name="_remove_from_cart")
     * @Template()
     */
    public function removeFromCartAction(Request $request, $itemId, $hash)
    {
        if($request->isXmlHttpRequest()) {
            return "hello";
        } else {
            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
            $item = $em->getRepository('DTRBundle:Item')->find($itemId);
            $shopName = $item->getProduct()->getShop()->getSlug();
            $member = $item->getMember();
            $member->removeItem($item);
            $em->remove($item);

            $em->flush();

            return $this->redirectToRoute('_shop', ['hash' => $event[0]->getHash(), 'shopName' => $shopName]);
        }
    }

    /**
     * @Route("/event/{hash}/remove-from-cart/{itemId}", name="_remove_from_cart_ajax")
     * @Template()
     * @Method({"POST"})
     */
    public function removeFromCartAjaxAction(Request $request, $itemId, $hash)
    {
        if($request->isXmlHttpRequest()) {
            return new Response("HELLO!");
        } else {
            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
            $item = $em->getRepository('DTRBundle:Item')->find($itemId);
            $shopName = $item->getProduct()->getShop()->getSlug();
            $member = $item->getMember();
            $member->removeItem($item);
            $em->remove($item);

            $em->flush();

            return $this->redirectToRoute('_shop', ['hash' => $event[0]->getHash(), 'shopName' => $shopName]);
        }
    }

    /**
     * @Route("/event/{hash}/delete-cart", name="_delete_cart")
     * @Template()
     */
    public function deleteCartAction(Request $request, $hash)
    {
        if($request->isXmlHttpRequest()) {
            return new Response("HELLO!");
        } else {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
            $member = $em->getRepository('DTRBundle:Member')->findByEventUser($event[0], $user);
            $items = $em->getRepository('DTRBundle:Item')->findByMember($member);
            $shopName = $items[0]->getProduct()->getShop()->getSlug();
            foreach ($items as $item) {
                $em->remove($item);
            }
            $em->flush();


            return $this->redirectToRoute('_shop', ['hash' => $event[0]->getHash(), 'shopName' => $shopName]);
        }


    }


}