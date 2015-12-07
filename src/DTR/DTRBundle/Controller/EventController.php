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
     * @return Response
     *
     * @Route("/test")
     */
    public function testAction()
    {
        $doctrine = $this->getDoctrine();

        $event = $doctrine->getRepository('DTRBundle:Event')->findOneByHash('283682');

        list($host, $guests) = $event->getHostAndGuests();

        return $this->render('event/dashboard/host.html.twig', compact('event', 'host', 'guests'));
    }

    /**
     * Displays a form to create a new Event entity.
     *
     * @Route("/new", name="new_event")
     * @Method("GET")
     */
    public function newAction()
    {
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

        $form->add('submit', 'submit', array('label' => 'Sukurti'));

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

            $user
                ->addEvent($event);

            $em->persist($event);
            $em->persist($user);
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
     * @param Event $event
     * @return mixed
     * @internal param $hash
     *
     * @Route("/{hash}", name="dashboard")
     */
    public function dashboardAction(Event $event)
    {
        $doctrine = $this->getDoctrine();

        $user = $this->getUser();
        $member = $doctrine->getRepository('DTRBundle:Member')->findByEventUser($event, $user);
        $items = $doctrine->getRepository('DTRBundle:Item')->findByMember($member);
        $totalCost = 0.0;
        $totalItems = 0;
        foreach ($items as $item) {
            $productPrice = $item->getProduct()->getPrice() * $item->getQuantity();
            $totalCost += $productPrice;
            $totalItems += $item->getQuantity();
        }

        if($member == null) {
            return $this->render('event/dashboard/join.html.twig', [ 'event' => $event ]);
        }

        if($member->isHost()) {
            $guests = $event->getGuests();

            return $this->render('event/dashboard/host.html.twig', [
                'event' => $event,
                'host' => $member,
                'guests' => $guests,
                'items' => $items,
                'totalCost' => $totalCost,
                'totalItems' => $totalItems
            ]);
        }   

        list($host, $guests) = $event->getHostAndGuests();

        return $this->render('event/dashboard/guest.html.twig', [
            'event' => $event,
            'host' => $host,
            'guests' => $guests,
            'current' => $member,
            'items' => $items,
            'totalCost' => $totalCost,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * @param Event $event
     * @return Response
     *
     * @Route("/{hash}/overview", name="_overview")
     */
    public function overviewAction(Event $event)
    {
        return $this->render('event/overview.html.twig', [ 'event' => $event ]);
    }

    /**
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{hash}/join", name="join_event")
     */
    public function joinAction(Event $event)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $member = new Member();

        $member
            ->setEvent($event)
            ->setUser($user);

        $em->persist($member);
        $em->flush();

        return $this->redirectToRoute('dashboard', [ 'hash' => $event->getHash() ]);
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

        $form->add('submit', 'submit', array('label' => 'Atnaujinti'));

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

    /**
     *
     * @Route("/{hash}/remove-member/{memberId}", name="_remove_member")
     */
    public function removeMemberAction($memberId, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
        $member = $em->getRepository('DTRBundle:Member')->find($memberId);

        $event[0]->removeMember($member);
        $em->remove($member);
        $em->flush();

        return $this->forward(
            'DTRBundle:Event:dashboard',
            array(
            'event' => $event[0],
            'hash' => $event[0]->getHash()
            )
        );
    }

    /**
     *
     * @Route("/{hash}/remove-debt/{memberId}", name="_remove_debt")
     */
    public function removeDebtAction($memberId, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('DTRBundle:Member')->find($memberId);
        $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
        $member->decreaseDebt($member->getDebt());
        $em->flush();

        return $this->forward(
            'DTRBundle:Event:dashboard',
            array(
                'hash' => $hash,
                'event' => $event,
                'member' => $member
            )
        );
    }

    /**
     *
     * @Route("/{hash}/make-host/{memberId}", name="_make_host")
     */
    public function makeHostAction($memberId, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('DTRBundle:Member')->find($memberId);
        $event = $em->getRepository('DTRBundle:Event')->findByHash($hash);
        $member->setHost();
        $em->flush();

        return $this->forward(
            'DTRBundle:Event:dashboard',
            array(
                'event' => $event[0],
                'hash' => $event[0]->getHash()
            )
        );
    }

    /**
     *
     * @Route("/{hash}/check-order/{memberId}", name="_check_order")
     */
    public function checkOrderAction($hash, $memberId)
    {
        $em = $this->getDoctrine()->getManager();

        $member = $em->getRepository('DTRBundle:Member')->find($memberId);
        $items = $em->getRepository('DTRBundle:Item')->findByMember($member);

        $totalCost = 0.0;
        foreach ($items as $item) {
            $productPrice = $item->getProduct()->getPrice() * $item->getQuantity();
            $totalCost += $productPrice;
        }

        return $this->render(
            'views/dashboard/order.html.twig',
            array(
                'items' => $items,
                'totalCost' => $totalCost
            )
        );
    }

    /**
     * @param Event $event
     * @param $made
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{hash}/{made}")
     */
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