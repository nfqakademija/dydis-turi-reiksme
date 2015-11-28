<?php

namespace DTR\DTRBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
}