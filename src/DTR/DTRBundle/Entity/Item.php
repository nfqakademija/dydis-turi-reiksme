<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="item")
 */
class Item
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop", inversedBy="items")
     */
    private $shop;

    /**
     * @var ItemType
     *
     * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items")
     */
    private $itemType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="price", type="float", scale=2)
     */
    private $price;

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
     * Set shop
     *
     * @param \DTR\DTRBundle\Entity\Shop $shop
     *
     * @return Item
     */
    public function setShop(Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return Shop
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Set itemTypeId
     *
     * @param ItemType $itemType
     *
     * @return Item
     */
    public function setItemType(ItemType $itemType = null)
    {
        $this->itemType = $itemType;
        return $this;
    }

    /**
     * Get itemType
     *
     * @return ItemType
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
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
     * Set price
     *
     * @param float $price
     *
     * @return Item
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemType = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add itemType
     *
     * @param \DTR\DTRBundle\Entity\ItemType $itemType
     *
     * @return Item
     */
    public function addItemType(\DTR\DTRBundle\Entity\ItemType $itemType)
    {
        $this->itemType[] = $itemType;

        return $this;
    }

    /**
     * Remove itemType
     *
     * @param \DTR\DTRBundle\Entity\ItemType $itemType
     */
    public function removeItemType(\DTR\DTRBundle\Entity\ItemType $itemType)
    {
        $this->itemType->removeElement($itemType);
    }
}
