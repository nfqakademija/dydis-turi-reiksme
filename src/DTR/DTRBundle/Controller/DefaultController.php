<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Event;
use DTR\DTRBundle\Entity\Item;
use DTR\DTRBundle\Entity\Member;
use DTR\DTRBundle\Entity\Product;
use DTR\DTRBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter;


class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function nameAction($name)
    {
        return $this->render(
            'views/default/name.html.twig',
            array('name' => $name)
        );
    }

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
     * @Route("/shops", name="_shops_list")
     * @Template()
     */
    public function shopsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $shops = $em->getRepository('DTRBundle:Shop')->FindAllShops();

//        $form = $this->createFormBuilder()
//            ->add('search', 'submit', array('label' => 'Search'))
//            ->getForm();
//
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            // ... perform some action, such as saving the task to the database
//
//            // return $this->redirectToRoute('task_success');
//        }

        return $this->render(
            'views/menu/shops.html.twig',
            array(
                'shops' => $shops//,
                //'form' => $form->createView())
            )
        );
    }

    /**
     * @Route("/shops/{shop_name}", name="_shop")
     * @Template()
     */
    public function shopAction($shop_name)
    {
        $em = $this->getDoctrine()->getManager();

        $shop = $em->getRepository('DTRBundle:Shop')->findShopId($shop_name);

        $products = $em->getRepository('DTRBundle:Product')->findAllShopProducts($shop);

        return $this->render(
            'views/menu/products.html.twig',
            array('products' => $products)
        );
    }

    /**
     * @param $request
     * @return Response
     *
     * @Route("create_event", name="create_event")
     */
    public function createAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event_response = $this->forward('DTRBundle:Default:persist', compact('event'));

            return $event_response;
        }

        return $this->render('views/forms/create_event.html.twig', [
            'form' => $form->createView()
        ]);
    }

//    /**
//     *
//     * @Route("create_event", name="create_event")
//     */
//    public function shopSearchAction()
//    {
//
//    }

    /**
     * @param $event
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/persist", name="persist")
     */
    public function persistAction(Event $event)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->persist($event);
        $manager->flush();

        return new Response('Created event: #'. $event->getHash());
    }

    /**
     * @return Response
     *
     * @Route("/test")
     */
    public function testAction()
    {

    }

    /**
     *
     * @Route("/db")
     */
    public function dbAction()
    {
        $shop =  $this->getDoctrine()->getRepository('DTRBundle:Shop')->find(2);

        $product = new Product();
        $product->setName('Pica CanCan1');
        $product->setPrice(5.40);
        $product->setShop($shop);
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);

        return new Response('Created product id '.$product->getId());
    }

    /**
     *
     * @Route("/up")
     */
    public function upAction()
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('DTRBundle:Shop')->find(2);
        $product->setSlug('can_can');

        $product = $em->getRepository('DTRBundle:Shop')->find(3);
        $product->setSlug('panda_kinija');

        $product = $em->getRepository('DTRBundle:Shop')->find(4);
        $product->setSlug('kinu_vysnia');

        $product = $em->getRepository('DTRBundle:Shop')->find(5);
        $product->setSlug('jammi_kebabai');

        $product = $em->getRepository('DTRBundle:Shop')->find(6);
        $product->setSlug('alikebabai');

        $em->flush();

        return new Response('Updated product id '.$product->getId());
    }
}