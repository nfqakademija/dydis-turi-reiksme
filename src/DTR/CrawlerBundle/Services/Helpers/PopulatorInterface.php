<?php

namespace DTR\CrawlerBundle\Services\Helpers;

interface PopulatorInterface
{
    /**
     * @param array $menu
     * @param $shop_name
     * @return mixed
     */
    public function populateMenu(array $menu, $shop_name);
}