<?php

namespace DTR\DTRBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_index")
     * @Template()
     */
    public function indexAction()
    {
        $name = "";

        return $this->render(
            'views/default/name.html.twig',
            array('name' => $name)
        );
    }


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
