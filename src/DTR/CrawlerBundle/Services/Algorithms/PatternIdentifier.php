<?php

namespace DTR\CrawlerBundle\Services\Algorithms;

use DTR\CrawlerBundle\Services\Validators\ValidationerInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class PatternIdentifier implements PatternIdentifierInterface
{
    /**
     * @var ValidationerInterface
     */
    protected $validationer;

    /**
     * @param ValidationerInterface $validationer
     */
    public function __construct(ValidationerInterface $validationer)
    {
        $this->validationer = $validationer;
    }

    /**
     * @param Crawler $document
     * @return array
     */
    public function getProductHtmlCollection(Crawler $document)
    {
        $beginning_of_loop = true;
        $best_result = array();
        $best_expertise = array();

        do
        {
            $fresh_result = $this->executeAlgorithm($document);
            $fresh_expertise = $this->validationer->assertResult($fresh_result);

            if($beginning_of_loop)
            {
                $best_result = $fresh_result;
                $best_expertise = $fresh_expertise;

                $beginning_of_loop = false;

                continue;
            }

            $comparison_value = $this->validationer->compareResults($fresh_expertise, $best_expertise);

            $this->swapResults($fresh_result, $fresh_expertise, $best_result, $best_expertise, $comparison_value);
        }
        while($this->validationer->loop());

        return $best_result;
    }

    /**
     * @param array $fresh_result
     * @param array $fresh_expertise
     * @param array $best_result
     * @param array $best_expertise
     * @param $comparison
     */
    public function swapResults(array &$fresh_result, array &$fresh_expertise, array &$best_result, array &$best_expertise, $comparison)
    {
        switch($comparison)
        {
            case 2:
                break;
            case 1:
                $best_result = $fresh_result;
                $best_expertise = $fresh_expertise;

                break;
            case 0:
                foreach($fresh_result as  $result)
                    $best_result[] = $result;
        }
    }

    /**
     * @param Crawler $document
     * @return array
     */
    abstract public function executeAlgorithm(Crawler $document);
}