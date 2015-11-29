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

            return $this->render(
                'views/dashboard/dashboard.html.twig',
                array(
                    'user' => $user,
                    'event' => $event,
                    'member' => $member
                )
            );
        }

        return new Response('Is a simple member. Need a simple view.');
    }

    /**
     *
     * @Route("/remove-member/{memberId}/{eventHash}", name="_remove_member")
     */
    public function removeMemberAction($memberId, $eventHash)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('DTRBundle:Event')->findByHash($eventHash);
        $member = $em->getRepository('DTRBundle:Member')->find($memberId);

        $event[0]->removeMember($member);
        $em->remove($member);
        $em->flush();

        return new Response($member->getId() . $event[0]->getHash());
    }

    /**
     *
     * @Route("/remove-debt/{memberId}", name="_remove_debt")
     */
    public function removeDebtAction($memberId)
    {
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('DTRBundle:Member')->find($memberId);
        $member->decreaseDebt($member->getDebt());
        $em->flush();

        return new Response($member->getId());
    }

    /**
     *
     * @Route("/make-host/{/memberId}", name="_make_host")
     */
    public function makeHostAction($memberId)
    {
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('DTRBundle:Member')->find($memberId);
        $member->setHost();
        $em->flush();

        return new Response($member->getId());
    }
}