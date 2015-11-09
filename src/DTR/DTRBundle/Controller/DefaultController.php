<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            array('name' => $shops)
        );
    }


    public function hashAction($event)
    {
        // Generating unique hash code
        $uniqueHash = $event->getHash();

        // Generate unique URL
        $uniqueURL = "http://test.com/" . $uniqueHash;

        // Object responsible for handling the process of persisting objects to the database
        $entityManager = $this->getDoctrine()->getManager();

        // "Managing" the $event object, this doesn't cause a query to the database
        $entityManager->persist($event);

        // $event objest is persisted to the database
        $entityManager->flush();

        return $this->render(
            'views/default/url.html.twig',
            array('uniqueURL' => $uniqueURL
        ));
    }
    
}
