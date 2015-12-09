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

    /**
     * @param $url string
     * @return string
     */
    public function getUrlContents($url)
    {
        $curl_handle=curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

        $contents = curl_exec($curl_handle);

        curl_close($curl_handle);

        return $contents;
    }

    /**
     * @Route("/services")
     */
    public function testAction()
    {
        $class_matcher = $this->get('class_matcher');

        $str = 'Not set';

        if($class_matcher->isConstructed())
            $str = 'Set';

        return new Response($str);
    }
}
