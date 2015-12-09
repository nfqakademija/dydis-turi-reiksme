<?php

namespace DTR\CrawlerBundle\Services\Inspectors;

use Symfony\Component\DomCrawler\Crawler;

class ComponentInspector extends Inspector
{
    const IMAGE_KEYWORDS = [
        'image',
        'img',
        'picture',
        'pic',
        'photo',
        'thumbnail',
        'thumb',
        'gallery',
        'box'
    ];

    const TITLE_KEYWORDS = [
        'title',
        'heading',
        'name',
        'pavadinimas',
        'antraste'
    ];

    const  BAD_PRICE_KEYWORDS = [
        'discount',
        'stripped',
        'nuolaida',
        'old',
        'deprecated',
        'sena'
    ];

    /**
     * @var string
     */
    protected $base_url;

    /**
     * @param $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
    }

    /**
     * @param Crawler $product_dom
     * @return string
     */
    public function getImage(Crawler $product_dom)
    {
        $images = $this->getImages($product_dom);

        if($images == null)
            return null;

        $count_images = count($images);

        if($count_images == 1)
            return $this->getSource($images[0]);

        $image = $this->getBestComponent($images, $count_images, self::IMAGE_KEYWORDS);

        return $this->getSource($image);
    }

    /**
     * @param Crawler $product_dom
     * @return string
     */
    public function getTitle(Crawler $product_dom)
    {
        if(($titles = $this->getTitlesByTags($product_dom)) != null)
            return $this->getCleanText($titles[0]);

        if(($titles = $this->getByAttributes($product_dom, self::TITLE_KEYWORDS, 'p, span')) == null)
            return null;

        $count_titles = count($titles);

        if($count_titles == 1)
            return $this->getCleanText($titles[0]->text());

        $title = $this->getBestComponent($titles, $count_titles, self::TITLE_KEYWORDS);

        return $this->getCleanText($title->text());
    }

    /**
     * @param Crawler $product_dom
     * @return string
     */
    public function getDescription(Crawler $product_dom)
    {
        if(($descriptions = $this->getDescriptions($product_dom)) == null)
            return '';

        return $this->getCleanText($descriptions[0]->text());
    }

    /**
     * @param Crawler $all_elements
     * @return array
     */
    public function getPricesAndHtml(Crawler $all_elements)
    {
        $html = array();
        $prices = array();

        foreach($all_elements as $element)
        {
            if(preg_match_all('/(\d+(\.\d{1,}|,\d{1,})?) *(€|Eur|eur|EUR)/i', $element->nodeValue, $matches) > 0)
            {
                $element = new Crawler($element);
                $info = $this->getPricesAndHtml($element->filter('*')->slice(1));

                if(empty($info[0]))
                {
                    foreach($matches[0] as $match)
                    {
                        $html[] = $element;
                        $prices[] = $match;
                    }
                }
            }
        }

        return [ $html, $prices ];
    }

    /**
     * @param Crawler $product_dom
     * @return array
     */
    public function getPriceInfo(Crawler $product_dom)
    {
        $all_elements = $product_dom->filter('*');
        list($blocks, $prices) = $this->getPricesAndHtml($all_elements);

        $prices_total = count($prices);
        $keyword_str = '/('. implode('|', self::BAD_PRICE_KEYWORDS). ')/i';

        $new_blocks = array();
        $new_prices = array();

        for($b = 0; $b != $prices_total; ++$b)
        {
            if($this->isValidPriceBlock($blocks[$b], $prices_total, $keyword_str))
            {
                $new_blocks[] = $blocks[$b];
                $new_prices[] = $prices[$b];
            }
        }

        return [ $new_blocks, $new_prices ];
    }

    /**
     * @param Crawler $price_block
     * @param array $prices
     * @param $current_price
     * @return string
     */
    public function getPriceDifferences(Crawler $price_block, array $prices, $current_price)
    {
        $diff = '';
        $prices_present = false;

        while(!$prices_present)
        {
            $valid_block = $price_block;
            $diff = str_replace($current_price, '', $valid_block->text());
            $price_block = $price_block->parents()->first();

            foreach($prices as $price)
            {
                if($price == $current_price)
                    continue;

                if(strpos($price_block->text(), $price) !== FALSE)
                {
                    $prices_present = true;
                    break;
                }
            }
        }

        $diff = $this->getCleanText($diff);

        return (preg_match('/[a-zA-Z0-9]/i', $diff) == 0) ? '' : $diff;
    }

    /**
     * @param Crawler $block
     * @param $prices_total
     * @param $keyword_str
     * @return null|Crawler
     */
    public function isValidPriceBlock(Crawler $block, $prices_total, $keyword_str)
    {
        if(preg_match_all($keyword_str, $block->extract(['class'])[0]) != 0)
            return null;

        if(count($this->getPrices($block)) == $prices_total)
            return $block;

        return $this->isValidPriceBlock($block->parents()->first(), $prices_total, $keyword_str);
    }

    /**
     * @param array $components
     * @param $count
     * @param array $keywords
     * @return Crawler
     */
    public function getBestComponent(array $components, $count, array $keywords)
    {
        $best_component = $components[0];
        $best_score = $this->countBlockKeywords($best_component, $keywords);

        for($i = 1; $i != $count; ++$i)
        {
            $new_score = $this->countBlockKeywords($components[$i], $keywords);

            if($new_score > $best_score)
                $best_component = $components[$i];
        }

        return $best_component;
    }

    /**
     * @param Crawler $image
     * @return string
     */
    public function getSource(Crawler $image)
    {
        $source = $image->extract([ 'src' ])[0];

        if(strpos($source, $this->base_url) !== FALSE)
            return $source;

        $delimiter = '';

        if($source[0] != '/')
            $delimiter = '/';

        return $this->base_url. $delimiter. $source;
    }

    /**
     * @param $dirty
     * @return string
     */
    public function getCleanText($dirty)
    {
        $characters = str_split(preg_replace('/(\n|\r|\t)/i', '', trim($dirty)));
        $found_space = false;
        $count_chars = count($characters);

        for($c = 0; $c != $count_chars; ++$c)
        {
            if($characters[$c] == ' ')
            {
                if($found_space)
                    unset($characters[$c]);
                else
                    $found_space = true;
            }
            else
            {
                if($found_space)
                    $found_space = false;
            }
        }

        return implode('', array_values($characters));
    }
}