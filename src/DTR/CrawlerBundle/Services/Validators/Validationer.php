<?php

namespace DTR\CrawlerBundle\Services\Validators;

use DTR\CrawlerBundle\Services\Inspectors\Inspector;
use Symfony\Component\DomCrawler\Crawler;

class Validationer implements ValidationerInterface
{
    const RESULTS_TO_QUALIFY = 5;

    const DEFAULT_POINTS = [
        'count' => 1,
        'length' => 1,
        'score' => 1,
        'consistency' => 1,
        'mode' => 1
    ];

    private $file;

    /**
     * @var Inspector
     */
    private $inspector;

    /**
     * @var integer
     */
    private $indicator_stage;

    /**
     * @var array|boolean
     */
    private $indicators;

    /**
     * @var array
     */
    private $keywords;

    /**
     * @param Inspector $inspector
     */
    public function __construct(Inspector $inspector)
    {
        $this->file = getcwd(). '/src/DTR/CrawlerBundle/Resources/Files/test.csv';
        $this->inspector = $inspector;
        $this->indicator_stage = 1;

        $this->setIndicators();
        $this->readKeywords();
    }

    /**
     * Read keywords from file.
     */
    public function readKeywords()
    {
        $file = fopen($this->file, 'r');

        if($file === FALSE)
        {
            $this->keywords = array();
            return;
        }

        $this->keywords = fgetcsv($file);

        fclose($file);
    }

    /**
     * @return boolean
     */
    public function loop()
    {
        ++$this->indicator_stage;
        $this->setIndicators();

        return $this->indicators != false;
    }

    /**
     * @param array $expertise_one
     * @param array $expertise_two
     * @param array $points
     * @return int
     */
    public function compareResults(array $expertise_one, array $expertise_two, array $points = self::DEFAULT_POINTS)
    {
        if($expertise_one['does_qualify'] && !$expertise_two['does_qualify'])
            return 1;

        if(!$expertise_one['does_qualify'] && $expertise_two['does_qualify'])
            return 2;

        $left_corner = 0;
        $right_corner = 0;

        if($expertise_one['count'] != $expertise_two['count'])
            ($expertise_one['count'] > $expertise_two['count']) ? ($left_corner += $points['count']) : ($right_corner += $points['count']);

        if($expertise_one['length'] != $expertise_two['length'])
            ($expertise_one['length'] > $expertise_two['length']) ? ($left_corner += $points['length']) : ($right_corner += $points['length']);

        if($expertise_one['score'] != $expertise_two['score'])
            ($expertise_one['score'] > $expertise_two['score']) ? ($left_corner += $points['score']) : ($right_corner += $points['score']);

        if($expertise_one['consistency'] != $expertise_two['consistency'])
            ($expertise_one['consistency'] > $expertise_two['consistency']) ? ($left_corner += $points['consistency']) : ($right_corner += $points['consistency']);

        ($expertise_one['mode'] < $expertise_two['mode']) ? ($left_corner += $points['mode']) : ($right_corner += $points['mode']);


        if($left_corner > $right_corner)
            return 1;

        if($left_corner < $right_corner)
            return 2;

        return 0;
    }

    /**
     * @param array $result
     * @return array
     */
    public function assertResult(array $result)
    {
        $expertise = [
            'does_qualify' => false,
            'count' => 0,
            'length' => 0,
            'score' => 0,
            'consistency' => 0,
            'mode' => $this->indicator_stage
        ];

        if(empty($result))
            return $expertise;

        $results_count = count($result);

        $expertise['does_qualify'] = $results_count >= self::RESULTS_TO_QUALIFY;
        $expertise['count'] = $results_count;
        $expertise['length'] = $this->calculateAvgLength($result, $results_count);
        $expertise['score'] = $this->calculateAvgScore($result, $results_count);
        $expertise['consistency'] = $this->calculateConsistency($result, $results_count);
        //$expertise['mode'] = $this->indicator_stage;

        return $expertise;
    }

    /**
     * @param array $blocks
     * @param $blocks_count
     * @return float
     */
    public function calculateAvgLength(array $blocks, $blocks_count)
    {
        $sum_length = 0;

        foreach($blocks as $crawler)
            $sum_length += strlen($crawler->html());

        return $sum_length / $blocks_count;
    }

    /**
     * @param array $blocks
     * @param $blocks_count
     * @return float
     */
    public function calculateAvgScore(array $blocks, $blocks_count)
    {
        $sum_score = 0;

        foreach($blocks as $crawler)
            //$sum_score += $this->inspector->calculateScore($crawler, $this->keywords);
            $sum_score += $this->inspector->countBlockKeywords($crawler, $this->keywords);

        return $sum_score / $blocks_count;
    }

    /**
     * @param array $blocks
     * @param $blocks_count
     * @return float
     */
    public function calculateConsistency(array $blocks, $blocks_count)
    {
        $back_index = $blocks_count - 1;
        $comparison_structure = $this->lookupStructure($blocks[$back_index]);
        $negative = 0;

        for($b = 0; $b != $back_index; ++$b)
        {
            $block_structure = $this->lookupStructure($blocks[$b]);
            $negative -= $this->compareStructures($comparison_structure, $block_structure);
        }

        return $negative / $blocks_count;
    }

    /**
     * @param array $comparison_structure
     * @param array $subject_structure
     * @return int
     */
    public function compareStructures(array $comparison_structure, array $subject_structure)
    {
        $negative = $this->removeExtraChildren($comparison_structure, $subject_structure);
        $comparison_count = count($comparison_structure);

        for($i = 0; $i != $comparison_count; ++$i)
        {
            if($comparison_structure[$i]['tag'] != $subject_structure[$i]['tag'])
                --$negative;

            if($comparison_structure[$i]['children'] == null)
            {
                if($subject_structure[$i]['children'] != null)
                    $negative -= $this->countTreeElements($subject_structure[$i]['children']);
            }
            else
            {
                if($subject_structure[$i]['children'] == null)
                    $negative -= $this->countTreeElements($comparison_structure[$i]['children']);
                else
                    $negative -= $this->compareStructures($comparison_structure[$i]['children'], $subject_structure[$i]['children']);
            }
        }

        return $negative;
    }

    /**
     * @param Crawler $blocks
     * @return array
     */
    public function lookupStructure(Crawler $blocks)
    {
        $structure = array();

        foreach($blocks as $block)
        {
            $block = new Crawler($block);
            $children = $block->children();

            $children_structure = null;

            if ($children->getNode(0) != null)
                $children_structure = $this->lookupStructure($children);

            $structure[] = [ 'tag' => $block->nodeName(), 'children' => $children_structure ];
        }

        return $structure;
    }

    /**
     * @param array $comparison_structure
     * @param array $subject_structure
     * @return int
     */
    public function removeExtraChildren(array &$comparison_structure, array &$subject_structure)
    {
        $comparison_count = count($comparison_structure);
        $subject_count = count($subject_structure);

        if($comparison_count == $subject_count)
            return 0;

        $negative = 0;

        if($comparison_count < $subject_count)
        {
            for($s = $subject_count - 1; $s != $comparison_count - 1; --$s)
            {
                $last_element = array_pop($subject_structure);
                $negative -= $this->countTreeElements($last_element);
            }
        }

        if($subject_count < $comparison_count)
        {
            for($c = $comparison_count - 1; $c != $subject_count - 1; --$c)
            {
                $last_element = array_pop($comparison_structure);
                $negative -= $this->countTreeElements($last_element);
            }
        }

        return $negative;
    }

    /**
     * @param array $tree
     * @return int
     */
    public function countTreeElements(array $tree)
    {
        $count = 0;

        foreach($tree as $branch)
        {
            if(is_array($branch))
                $count += $this->countTreeElements($branch);
            else
            {
                if($branch != null)
                    ++$count;
            }
        }

        return $count;
    }

    /**
     * @param Crawler $block
     * @return boolean
     */
    public function isCriteriaMet(Crawler $block)
    {
        foreach($this->indicators as $indicator)
        {
            $function = 'get'. ucfirst($indicator);
            $condition = $this->inspector->$function($block) == null;

            if($condition)
                return false;
        }

        return true;
    }

    /**
     * Sets indicators
     */
    public function setIndicators()
    {
        switch($this->indicator_stage)
        {
            case 1:
                $this->indicators = [ 'images', 'titles', 'prices', 'descriptions' ];

                break;
            case 2:
                $this->indicators = [ 'images', 'titles', 'prices' ];

                break;
            case 3:
                $this->indicators = [ 'images', 'titles', 'descriptions' ];

                break;
            case 4:
                $this->indicators = [ 'images', 'titles' ];

                break;
            default:
                $this->indicators = false;
        }
    }
}