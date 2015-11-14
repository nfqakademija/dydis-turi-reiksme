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
     * @var string
     *
     * @ORM\Column(name="image_location", type="string", length=255)
     */
    private $imageLocation;

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
     * @ORM\OneToMany(targetEntity="Product", mappedBy="shop")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function setImageLocation($imageLocation)
    {
        $this->imageLocation = $imageLocation;

        return $this;
    }

    /**
     * Get imageLocation
     *
     * @return string
     */
    public function getImageLocation()
    {
        return $this->imageLocation;
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
     * Add product
     *
     * @param \DTR\DTRBundle\Entity\Product $product
     *
     * @return Shop
     */
    public function addProduct(\DTR\DTRBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \DTR\DTRBundle\Entity\Product $product
     */
    public function removeProduct(\DTR\DTRBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
