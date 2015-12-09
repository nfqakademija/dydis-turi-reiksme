<?php

namespace DTR\CrawlerBundle\Services\Validators;

use Symfony\Component\DomCrawler\Crawler;

interface ValidationerInterface
{
    /**
     * @return boolean
     */
    public function loop();

    /**
     * @param Crawler $block
     * @return boolean
     */
    public function isCriteriaMet(Crawler $block);

    /**
     * @param array $result
     * @return array
     */
    public function assertResult(array $result);

    /**
     * @param array $expertise_one
     * @param array $expertise_two
     * @return int
     */
    public function compareResults(array $expertise_one, array $expertise_two);

    /**
     * @param Crawler $blocks
     * @return array
     */
    public function lookupStructure(Crawler $blocks);

    /**
     * @param array $blocks
     * @param $blocks_count
     * @return float
     */
    public function calculateAvgLength(array $blocks, $blocks_count);
}