<?php

namespace DTR\DTRBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class DefaultController extends Controller 
{

    /**
     * @Route("/contacts", name="contacts")
     * @return Response
     */
    public function NewAction()
    {
        return Response;
    }

}
