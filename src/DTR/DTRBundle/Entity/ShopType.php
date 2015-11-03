<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ShopType
 *
 * @ORM\Table(name="shoptype")
 * @ORM\Entity
 */
class ShopType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Shop", mappedBy="shoptype")
     */
    private $shops;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ShopType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add shop
     *
     * @param \DTR\DTRBundle\Entity\Shop $shop
     *
     * @return ShopType
     */
    public function addShop(\DTR\DTRBundle\Entity\Shop $shop)
    {
        $this->shops[] = $shop;

        return $this;
    }

    /**
     * Remove shop
     *
     * @param \DTR\DTRBundle\Entity\Shop $shop
     */
    public function removeShop(\DTR\DTRBundle\Entity\Shop $shop)
    {
        $this->shops->removeElement($shop);
    }

    /**
     * Get shops
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShops()
    {
        return $this->shops;
    }
}
