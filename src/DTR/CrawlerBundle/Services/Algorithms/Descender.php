<?php

namespace DTR\CrawlerBundle\Services\Algorithms;

use Symfony\Component\DomCrawler\Crawler;

class Descender extends PatternIdentifier
{
    /**
     * @param Crawler $document
     * @return array
     */
    public function executeAlgorithm(Crawler $document)
    {
        return $this->descend($document->filter('body'));
    }

    /**
     * @param Crawler $blocks
     * @return array
     */
    public function descend(Crawler $blocks)
    {
        $result_blocks = array();

        foreach($blocks as $block)
        {
            $block = new Crawler($block);

            if($this->validationer->isCriteriaMet($block))
            {
                $children = $block->children();

                if($children->getNode(0) == null)
                    $result_blocks[] = $block;
                else
                {
                    $children_results = $this->descend($children);

                    if(empty($children_results))
                        $result_blocks[] = $block;
                    else
                    {
                        foreach($children_results as $result)
                            $result_blocks[] = $result;
                    }
                }
            }
        }

        return $result_blocks;
    }

    public function __toString()
    {
        return __CLASS__;
    }
}