<?php

namespace DTR\CrawlerBundle\Services\Algorithms;

use Symfony\Component\DomCrawler\Crawler;

class ClassMatcher extends PatternIdentifier
{
    /**
     * @param Crawler $document
     * @return array
     */
    public function executeAlgorithm(Crawler $document)
    {
        $blocks = $this->getHtmlBlocks($document->filter('div, ul, li, table, td, header, footer, article'));

        if(empty($blocks))
            return array();

        return $this->getBestSiblings($blocks);
    }

    /**
     * @param array $collection
     * @return array
     */
    public function getBestSiblings(array $collection)
    {
        $collection_count = count($collection);
        $best_siblings = $collection[0];
        $best_quantity = count($best_siblings);
        $best_length = $this->validationer->calculateAvgLength($best_siblings, $best_quantity);

        for($c = 1; $c != $collection_count; ++$c)
        {
            $siblings_quantity = count($collection[$c]);

            if($siblings_quantity == $best_quantity)
            {
                $siblings_length = $this->validationer->calculateAvgLength($collection[$c], $siblings_quantity);

                if($siblings_length < $best_length)
                {
                    $best_siblings = $collection[$c];
                    $best_length = $siblings_length;
                }
            }
            else
            {
                if($siblings_quantity > $best_quantity)
                {
                    $best_siblings = $collection[$c];
                    $best_quantity = $siblings_quantity;
                }

            }
        }

        return $best_siblings;
    }

    /**
     * @param Crawler $blocks
     * @return array
     */
    public function getHtmlBlocks(Crawler $blocks)
    {
        $siblings_list = array();
        $information_list = array();

        foreach($blocks as $block)
        {
            list($new_siblings, $new_information) = $this->getSimilarSiblingsInfo(new Crawler($block));

            $match_index = $this->matchesInformation($new_information, $information_list);

            if($match_index > -1)
            {
                if(!$this->matchesText($new_siblings, $siblings_list[$match_index]))
                {
                    foreach($new_siblings as $new_sibling)
                        $siblings_list[$match_index][] = $new_sibling;
                }
            }
            else
            {
                if($this->validSiblings($new_siblings))
                {
                    $siblings_list[] = $new_siblings;
                    $information_list[] = $new_information;
                }
            }
        }

        return $siblings_list;
    }

    /**
     * @param array $siblings
     * @return bool
     */
    public function validSiblings(array &$siblings)
    {
        $siblings_count = count($siblings);
        $s = 0;

        while($s != $siblings_count)
        {
            if(!$this->validationer->isCriteriaMet($siblings[$s]))
            {
                array_splice($siblings, $s, 1);
                --$siblings_count;
            }
            else
                ++$s;

        }

        return !empty($siblings);
    }

    /**
     * @param array $new_information
     * @param array $information_list
     * @return int
     */
    public function matchesInformation(array $new_information, array $information_list)
    {
        $information_list_count = count($information_list);

        for($i = 0; $i != $information_list_count; ++$i)
        {
            $tag_matches = $new_information['tag'] == $information_list[$i]['tag'];
            //$classes_match = !empty(array_intersect($information_list[$i]['classes'], $new_information['classes']));
            $classes_match = $information_list[$i]['classes'] == $new_information['classes'];

            if($tag_matches && $classes_match)
                return $i;
        }

        return -1;
    }

    /**
     * @param array $new_siblings
     * @param array $comparison_siblings
     * @return bool
     */
    public function matchesText(array &$new_siblings, array $comparison_siblings)
    {
        $new_siblings_count = count($new_siblings);
        $s = 0;

        while($s != $new_siblings_count)
        {
            if($this->textExists($new_siblings[$s], $comparison_siblings))
            {
                array_splice($new_siblings, $s, 1);
                --$new_siblings_count;
            }
            else
                ++$s;
        }

        //Issues here with ! operator.
        return empty($new_siblings);
    }

    /**
     * @param Crawler $new_sibling
     * @param array $existing_siblings
     * @return bool
     */
    public function textExists(Crawler $new_sibling, array $existing_siblings)
    {
        $new_sibling_text = $new_sibling->text();

        foreach($existing_siblings as $existing_sibling)
        {
            if($new_sibling_text == $existing_sibling->text())
                return true;
        }

        return false;
    }

    /**
     * @param Crawler $block
     * @return array
     */
    public function getSimilarSiblingsInfo(Crawler $block)
    {
        $all_siblings = $block->parents()->first()->children();
        $siblings = [ $block ];

        $sample_node = $block->getNode(0);
        $sample_tag = $block->nodeName();
        $sample_classes = explode(' ', $block->extract([ 'class' ])[0]);

        foreach($all_siblings as $sibling)
        {
            if($sample_node->isSameNode($sibling))
                continue;

            $to_add = false;

            if($sibling->nodeName == $sample_tag)
            {
                if($sample_classes === FALSE)
                    $to_add = true;
                else
                {
                    $sibling_classes = explode(' ', $sibling->getAttribute('class'));
                    $matched_classes = array_intersect($sample_classes, $sibling_classes);

                    if(!empty($matched_classes))
                    {
                        if($matched_classes != $sample_classes)
                            $sample_classes = $matched_classes;

                        $to_add = true;
                    }
                }
            }

            if($to_add)
                $siblings[] = new Crawler($sibling);
        }

        return [ $siblings, [ 'tag' => $sample_tag, 'classes' => $sample_classes ] ];
    }
}