<?php

namespace DTR\CrawlerBundle\Services\Crawler;

use DTR\CrawlerBundle\Services\Algorithms\PatternIdentifierInterface;

interface CrawlerInterface
{
    /**
     * @param $url
     * @return array
     */
    public function getMenu($url);

    /**
     * @param PatternIdentifierInterface $pattern
     * @return CrawlerInterface
     */
    public function setPattern(PatternIdentifierInterface $pattern);
}