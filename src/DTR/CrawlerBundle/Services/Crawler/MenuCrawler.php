<?php

namespace DTR\CrawlerBundle\Services\Crawler;

use DTR\CrawlerBundle\Services\Algorithms\PatternIdentifierInterface;
use DTR\CrawlerBundle\Services\Inspectors\ComponentInspector;
use Symfony\Component\DomCrawler\Crawler;

class MenuCrawler implements CrawlerInterface
{
    /**
     * @var ComponentInspector
     */
    private $component_inspector;

    /**
     * @var PatternIdentifierInterface
     */
    private $pattern;

    /**
     * @param ComponentInspector $component_inspector
     */
    public function __construct(ComponentInspector $component_inspector)
    {
        $this->component_inspector = $component_inspector;
    }

    /**
     * @param PatternIdentifierInterface $pattern
     * @return CrawlerInterface
     */
    public function setPattern(PatternIdentifierInterface $pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @param $url
     * @return array
     */
    public function getMenu($url)
    {
        $contents = $this->chewUrl($url);
        $crawler = new Crawler($contents);

        $product_html = $this->pattern->getProductHtmlCollection($crawler);
        $products = array();

        foreach($product_html as $product_dom)
        {
            $collection = $this->getProductValues($product_dom);

            foreach($collection as $product)
                $products[] = $product;
        }

        $logo = $this->getLogo($crawler->filter('body'));

        if(empty($logo))
            $logo = $products[0]['image'];

        return [
            'logo' => $logo,
            'products' => $products
        ];
    }

    /**
     * @param Crawler $body
     * @return string
     */
    public function getLogo(Crawler $body)
    {
        $all_elements = $body->filter('*')->slice(1);

        foreach($all_elements as $element)
        {
            if($element->hasAttributes())
            {
                $attr_str = '';

                foreach($element->attributes as $attr)
                    $attr_str .= $attr->nodeName. ' '. $attr->nodeValue. ' ';

                if(preg_match_all('/logo/i', $attr_str) > 0)
                {
                    $element = new Crawler($element);
                    $element = $element->filter('img');

                    if($element->getNode(0) != null)
                        return $this->component_inspector->getSource($element);
                }
            }
        }

        return '';


        /*$all_elements = $all_elements->reduce(function(Crawler $element) {
            $classes = $element->extract([ 'class' ]);

            if(empty($classes[0]))
                return false;

            if(preg_match_all('/logo/i', $classes[0]) == 0)
                return false;

            $image = $element->filter('img');

            if($image->getNode(0) == null)
                return false;

            return true;
        });

        $logo = '';

        if($all_elements->getNode(0) != null)
            $logo = $this->component_inspector->getSource($all_elements->first());

        return $logo;*/
    }

    /**
     * @param Crawler $product_dom
     * @return array
     */
    public function getProductValues(Crawler $product_dom)
    {
        $image = $this->component_inspector->getImage($product_dom);
        $title = $this->component_inspector->getTitle($product_dom);
        $description = $this->component_inspector->getDescription($product_dom);

        list($blocks, $prices) = $this->component_inspector->getPriceInfo($product_dom);
        $price_count = count($prices);

        $values = array();

        if($price_count == 1)
        {
            $values[] = [
                'image' => $image,
                'title' => $title,
                'description' => $description,
                'price' => $this->component_inspector->getPriceFormat($prices[0])
            ];
        }
        else
        {
            for($p = 0; $p != $price_count; ++$p)
            {
                $diff = $this->component_inspector->getPriceDifferences($blocks[$p], $prices, $prices[$p]);
                $price = $this->component_inspector->getPriceFormat($prices[$p]);

                $values[] = [
                    'image' => $image,
                    'title' => $diff. ' '. $title,
                    'description' => $description,
                    'price' => $price
                ];
            }
        }

        return $values;
    }

    /**
     * @param string $url
     * @return string
     */
    public function chewUrl($url)
    {
        $url_parts = parse_url($url);
        $base_url = $url_parts['scheme']. '://'. $url_parts['host'];

        $this->component_inspector->setBaseUrl($base_url);

        return $this->getUrlContents($url);
    }

    /**
     * @param $url string
     * @return string
     */
    public function getUrlContents($url)
    {
        $curl_handle=curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

        $contents = curl_exec($curl_handle);

        curl_close($curl_handle);

        return ($contents === FALSE) ? $this->getUrlContents($url) : $contents;
    }
}