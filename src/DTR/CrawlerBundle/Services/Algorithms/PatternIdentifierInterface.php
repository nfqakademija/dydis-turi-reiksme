<?php

namespace DTR\CrawlerBundle\Services\Algorithms;

use Symfony\Component\DomCrawler\Crawler;

interface PatternIdentifierInterface
{
    /**
     * @param Crawler $document
     * @return Crawler
     */
    public function getProductHtmlCollection(Crawler $document);
}