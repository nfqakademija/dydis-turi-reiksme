<?php

namespace DTR\CrawlerBundle\Services\Inspectors;

use Symfony\Component\DomCrawler\Crawler;

class Inspector
{
    /**
     * @param Crawler $block
     * @return array|null
     */
    public function getImages(Crawler $block)
    {
        $images = $block->filter('img');

        if($images->getNode(0) == null)
            return null;

        //$sources = array();
        $image_list = array();

        foreach($images as $image)
        {
            $source = $image->attributes->getNamedItem('src');

            if ($source != null)
                $image_list[] = new Crawler($image);
        }

        return (empty($image_list)) ? null : $image_list;
    }

    /**
     * @param Crawler $block
     * @return array|null
     */
    public function getTitles(Crawler $block)
    {
        $titles = $this->getTitlesByTags($block);

        if($titles != null)
            return $titles;

        $keywords = [
            'title',
            'name',
            'pavadinimas'
        ];

        return $this->getByAttributes($block, $keywords);
    }

    /**
     * @param Crawler $block
     * @return array|null
     */
    public function getTitlesByTags(Crawler $block)
    {
        $titles = array();

        for($size = 1; $size <= 6; ++$size)
        {
            $hs = $block->filter('h'. $size);

            if($hs->getNode(0) != null)
            {
                foreach($hs as $h)
                    $titles[] = $h->nodeValue;
            }
        }

        return (empty($titles)) ? null : $titles;
    }

    /**
     * @param Crawler $block
     * @return array|null
     */
    public function getPrices(Crawler $block)
    {
        $results = preg_match_all('/(\d+(\.\d{1,}|,\d{1,})?) *(€|Eur|eur|EUR)/i', $block->html(), $matches);

        if($results == 0)
            return null;

        return $matches[0];
    }

    /**
     * @param $str
     * @return float
     */
    public function getPriceFormat($str)
    {
        $parts = explode(',', $str);

        if($parts[0] == $str)
            $parts = explode('.', $str);

        $above_one = (int)$parts[0];
        $below_one = (float)$parts[1] / 100;

        return $above_one + $below_one;
    }

    /**
     * @param Crawler $block
     * @return array|null
     */
    public function getDescriptions(Crawler $block)
    {
        $keywords = [
            'summary',
            'description',
            'overview',
            'recipe',
            'details',
            'meta',
            'text',
            'receptas',
            'sudetis',
            'aprasas',
            'aprasymas'
        ];

        $descriptions = $this->getByAttributes($block, $keywords, 'p, span');

        return ($descriptions == null) ? $this->getByParagraphLength($block) : $descriptions;
    }

    /**
     * @param Crawler $block
     * @return array|null
     */
    public function getByParagraphLength(Crawler $block)
    {
        $paragraphs = $block->filter('p')->reduce(function(Crawler $paragraph) {
            if($paragraph->children()->getNode(0) != null)
                return false;

            if(strlen($paragraph->text()) < 30)
                return false;

            return true;
        });

        if($paragraphs->first() == null)
            return null;

        $descriptions = array();

        foreach($paragraphs as $paragraph)
            $descriptions[] = new Crawler($paragraph);

        return $descriptions;
    }

    /**
     * @param Crawler $block
     * @param array $keywords
     * @param string $tags
     * @return array|null
     */
    public function getByAttributes(Crawler $block, array $keywords, $tags = '*')
    {
        $values = array();
        $regex = $this->quoteKeywords($keywords);

        $children = $block->filter($tags);

        foreach($children as $child)
        {
            if($child->hasAttributes())
            {
                $is_match = false;

                foreach($child->attributes as $attr)
                {
                    if(!$is_match)
                    {
                        if(preg_match('/'. $regex. '/i', $attr->nodeName. ' '. $attr->nodeValue))
                        {
                            $values[] = new Crawler($child);
                            $is_match = true;
                        }
                    }
                }
            }
        }

        return (empty($values)) ? null : $values;
    }

    /**
     * @param array $keywords
     * @return string
     */
    public function quoteKeywords(array $keywords)
    {
        $keywords = array_map('preg_quote', $keywords);

        return '('. implode('|', $keywords). ')';
    }

    /**
     * @param Crawler $block
     * @param array $keywords
     * @return int
     */
    public function countBlockKeywords(Crawler $block, array $keywords)
    {
        $this->climbToHighestParent($block);
        $keyword_str = '/('. implode('|', $keywords). ')/i';

        return $this->calculateScore($block, $keyword_str);
    }

    /**
     * @param Crawler $block
     */
    public function climbToHighestParent(Crawler &$block)
    {
        while(($block->siblings()->getNode(0)) == null)
            $block = $block->parents()->first();
    }

    /**
     * @param Crawler $block
     * @param string $keywords
     * @return int
     */
    public function calculateScore(Crawler $block, $keywords)
    {
        $all_elements = $block->filter('*');
        //$score = 0;

        //$preg_str = '/('. implode('|', $keywords). ')/i';
        $attr_str = '';

        foreach($all_elements as $element)
        {
            if($element->hasAttributes())
            {
                foreach($element->attributes as $attr)
                {
                    $attr_str .= $attr->nodeName. ' ';
                    $attr_str .= $attr->nodeValue. ' ';

                    /*$names_dashes = explode('-', $attr->nodeName);
                    $values = explode(' ', $attr->nodeValue);

                    if(count($names_dashes) > 0)
                    {
                        foreach($names_dashes as $name_dash)
                        {
                            if(in_array($name_dash, $keywords))
                                $score++;
                        }
                    }

                    if(count($values) > 0)
                    {
                        foreach($values as $value)
                        {
                            if(in_array($value, $keywords))
                                $score++;
                        }
                    }*/
                }
            }
        }

        //return $score;
        return preg_match_all($keywords, $attr_str);
    }
}