<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Event;
use DTR\DTRBundle\Entity\Member;
use DTR\DTRBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EventController
 *
 * @Route("/event")
 */
class EventController extends Controller
{
    /**
     * Displays a form to create a new Event entity.
     *
     * @Route("/new", name="new_event")
     * @Method("GET")
     */
    public function newAction()
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $event = new Event();
        $form   = $this->createCreateForm($event);

        return $this->render('event/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Creates a form to create a Event entity.
     *
     * @param Event $event
     * @return \Symfony\Component\Form\Form The form
     * @internal param Event $entity The entity
     *
     */
    private function createCreateForm(Event $event)
    {
        $form = $this->createForm(new EventType(), $event, array(
            'action' => $this->generateUrl('process_event'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new Event entity.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/new", name="process_event")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $event = new Event();

        $form = $this->createCreateForm($event);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $member = new Member();
            $user = $this->getUser();

            $member
                ->setEvent($event)
                ->setUser($user)
                ->setHost();

            $em->persist($event);
            $em->persist($member);

            $em->flush();

            return $this->redirect($this->generateUrl('dashboard', [ 'hash' => $event->getHash() ]));
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form'   => $form->createView()
        ]);
    }

    /**
     * @param $hash
     * @return mixed
     *
     * @Route("/{hash}", name="dashboard")
     */
    public function dashboardAction($hash)
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $doctrine = $this->getDoctrine();

        $event = $doctrine->getRepository('DTRBundle:Event')->findOneByHash($hash);
        $member = $doctrine->getRepository('DTRBundle:Member')->findByEventUser($event, $user);

        if($member == null) {
            return new Response('Not a member yet. Need a join button');
        }

        if($member->isHost()) {
            return new Response('Is a host. Need a moderator view.');
        }

        return new Response('Is a simple member. Need a simple view.');
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     * @Route("/{hash}/edit", name="edit_event")
     * @Method("GET")
     * @param Event $event
     * @return Response
     */
    public function editAction(Event $event)
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $editForm = $this->createEditForm($event);

        return $this->render('event/edit.html.twig', [
            'form' => $editForm->createView()
        ]);
    }

    /**
     * Creates a form to edit a Event entity.
     *
     * @param Event $event
     * @return \Symfony\Component\Form\Form The form
     * @internal param Event $entity The entity
     *
     */
    private function createEditForm(Event $event)
    {
        $form = $this->createForm(new EventType(), $event, array(
            'action' => $this->generateUrl('update_event', array('hash' => $event->getHash())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Event entity.
     *
     * @param Request $request
     * @param Event $event
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{hash}/edit", name="update_event")
     * @Method("PUT")
     */
    public function updateAction(Request $request, Event $event)
    {

        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($event);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard', array('hash' => $event->getHash())));
        }

        return $this->render('event/edit.html.twig', [
            'form' => $editForm->createView()
        ]);
    }

    public function paymentMadeAction(Event $event, $made)
    {
        switch($made)
        {
            case 'made':
                $event->setPaymentMade(true);

                break;
            case 'unmade':
                $event->setPaymentMade(false);

                break;
            default:
                throw $this->createNotFoundException('Puslapis nerastas!');
        }

        return $this->redirectToRoute('dashboard', [ 'hash' => $event->getHash() ]);
    }
}