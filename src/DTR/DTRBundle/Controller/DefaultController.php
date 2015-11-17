<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Event;
use DTR\DTRBundle\Entity\Product;
use DTR\DTRBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Tests\Fixtures\Entity;


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
     * @Route("/shops_list", name="_shops_list")
     * @Template()
     */
    public function shopAction()
    {
        // Repository object to fetch entities
        $repository = $this->getDoctrine()
            ->getRepository('DTRBundle:Shop');

        $shops = $repository->findAll();

        return $this->render(
            'views/default/shops_list.html.twig',
            array('shops' => $shops)
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
        $manager = $this->getDoctrine()->getManager();
        $str1 = $str2 = '';

        $productType = $manager->getRepository('DTRBundle:ProductType')->find(1);
        $shop = $manager->getRepository('DTRBundle:Shop')->find(1);

        $products = $productType->getProducts();

        foreach ($products as $product) {
            $str1 .= $product->getName(). ' ';
        }

        $products = $shop->getProducts();

        foreach ($products as $product) {
            $str2 .= $product->getName(). ' ';
        }

        return new Response($str1. '<br /><br />'. $str2);
    }

    /**
     *
     * @Route("/db")
     */
    public function dbAction()
    {
        $shop =  $this->getDoctrine()->getRepository('DTRBundle:Shop')->find(6);

        $product = new Product();
        $product->setName('Kebabas su paukstiena');
        $product->setPrice(7);
        $product->setShop($shop);

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        return new Response('Created product id '.$product->getId());
    }

    /**
     *
     * @Route("/up")
     */
    public function upAction()
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('DTRBundle:Product')->find(1);

        $product->setPrice(5.00);
        $em->flush();
    }
}