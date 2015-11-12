<?php

namespace DTR\DTRBundle\Controller;

use DTR\DTRBundle\Entity\Event;
use DTR\DTRBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class NewController extends WebTestCase
{
    /**
     * @Route("/new")
     */
    public function newAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new');

        $buttonCrawlerNode = $crawler->selectButton('submit');
        $form = $buttonCrawlerNode->form();

        /*
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Hello World")')->count()
        );
        */
    }

    /**
     * @Route("/fr", name="results")
     */
    public function frAction()
    {
        return new Response($_POST['dummy']);
    }
}
