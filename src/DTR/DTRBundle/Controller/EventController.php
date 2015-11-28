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

class EventController extends Controller
{
    /**
     * @param $hash
     * @return mixed
     *
     * @Route("/event/{hash}", name="dashboard")
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
     * Displays a form to create a new Event entity.
     *
     * @Route("/new_event", name="new_event")
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
            'event' => $event,
            'form'   => $form->createView()
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

        return $form;
    }

    /**
     * Creates a new Event entity.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/new_event", name="process_event")
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
     * Displays a form to edit an existing Event entity.
     *
     * @Route("/{id}/edit", name="_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DTRBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Event entity.
     *
     * @param Event $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Event entity.
     *
     * @Route("/{id}", name="_update")
     * @Method("PUT")
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DTRBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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