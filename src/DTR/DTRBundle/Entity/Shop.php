<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Shop
 *
 * @ORM\Table(name="shop")
 * @ORM\Entity
 */
class Shop
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="ShopType", inversedBy="shops")
     */
    private $shoptype;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="shop")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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
     * @return Shop
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
     * Set shoptype
     *
     * @param \DTR\DTRBundle\Entity\ShopType $shoptype
     *
     * @return Shop
     */
    public function setShoptype(\DTR\DTRBundle\Entity\ShopType $shoptype = null)
    {
        $this->shoptype = $shoptype;

        return $this;
    }

    /**
     * Get shoptype
     *
     * @return \DTR\DTRBundle\Entity\ShopType
     */
    public function getShoptype()
    {
        return $this->shoptype;
    }

    /**
     * Add item
     *
     * @param \DTR\DTRBundle\Entity\Item $item
     *
     * @return Shop
     */
    public function addItem(\DTR\DTRBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \DTR\DTRBundle\Entity\Item $item
     */
    public function removeItem(\DTR\DTRBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
