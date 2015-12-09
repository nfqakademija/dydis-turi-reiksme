<?php

namespace DTR\CrawlerBundle\Services\Helpers;

use DTR\CrawlerBundle\Services\Algorithms\PatternIdentifierInterface;

class EngineManager
{
    private $engines;

    private $current_engine;

    /**
     * @param PatternIdentifierInterface $pattern
     * @return bool
     */
    public function addEngine(PatternIdentifierInterface $pattern)
    {
        foreach($this->engines as $engine)
        {
            if((string)$engine == (string)$pattern)
                return false;
        }

        $this->engines[] = $pattern;

        return true;
    }

    public function selectEngine()
    {
        
    }

    /**
     * @param array $words
     * @return string
     */
    public function getClassName(array $words)
    {
        $word_count = count($words);

        if($word_count > 1)
            return $this->makeCamelcase($words, $word_count);

        return ucfirst($words[0]);
    }

    /**
     * @param array $words
     * @param $word_count
     * @return string
     */
    public function makeCamelcase(array $words, $word_count)
    {
        $camel = [ lcfirst($words[0]) ];

        for($w = 1; $w != $word_count; ++$w)
            $camel[] = ucfirst($words[$w]);

        return implode('', $camel);
    }
}