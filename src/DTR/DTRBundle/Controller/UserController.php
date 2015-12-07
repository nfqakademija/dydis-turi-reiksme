<?php

namespace DTR\DTRBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/info", name="_info")
     */
    public function infoAction()
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $doctrine = $this->getDoctrine();

        $hosted_events = $doctrine->getRepository('DTRBundle:Event')->findHostedEvents($user);
        //$participating_events = $doctrine->getRepository('DTRBundle:Event')->findParticipatingEvents($user);
        $participations = $doctrine->getRepository('DTRBundle:Member')->findParticipations($user);

        return $this->render('user/event_info.html.twig', compact('hosted_events', 'participations'));
    }
}