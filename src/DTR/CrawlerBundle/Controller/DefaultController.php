<?php

namespace DTR\CrawlerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/crawl")
     */
    public function indexAction()
    {
        $crawler = $this->get('menu_crawler');
        $pattern = $this->get('class_matcher');

        $crawler->setPattern($pattern);
        $menu = $crawler->getMenu('http://www.cili.lt/');

        //return new Response('Wrote to file.');
        return new JsonResponse($menu);
    }
}
