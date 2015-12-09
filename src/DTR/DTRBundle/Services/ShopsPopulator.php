<?php

namespace DTR\DTRBundle\Services;

use DTR\CrawlerBundle\Services\Helpers\PopulatorInterface;
use DTR\DTRBundle\Entity\Shop;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;

class ShopsPopulator extends Container implements PopulatorInterface
{
    /**
     * @param array $menu
     * @param $shop_name
     * @return mixed
     */
    public function populateMenu(array $menu, $shop_name)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        return 'How good is this?';
    }
}