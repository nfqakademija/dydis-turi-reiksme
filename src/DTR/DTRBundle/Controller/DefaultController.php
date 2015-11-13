<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Event;
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
}