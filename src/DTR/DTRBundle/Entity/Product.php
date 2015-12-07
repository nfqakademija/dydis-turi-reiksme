<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DTR\DTRBundle\Repository\ProductRepository")
 */
class Product
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
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var \DTR\DTRBundle\Entity\Shop
     *
     * @ORM\ManyToOne(targetEntity="Shop", inversedBy="products")
     */
    private $shop;


    /**
//     * @var \DTR\DTRBundle\Entity\Item
//     *
//     * @ORM\OneToMany(targetEntity="Item", inversedBy="product")
//     */
//    private $items;

    /**
     * @var \DTR\DTRBundle\Entity\ProductType
     *
     * @ORM\ManyToOne(targetEntity="ProductType", inversedBy="products")
     */
    private $productType;

    /**
     * @var string
     *
     * @ORM\Column(name="image_location", type="string", length=255)
     */
    private $imageLocation;

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
     * @return Product
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
     * @return Product
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
     * Set shop
     *
     * @param \DTR\DTRBundle\Entity\Shop $shop
     *
     * @return Product
     */
    public function setShop(Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \DTR\DTRBundle\Entity\Shop
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Set productType
     *
     * @param \DTR\DTRBundle\Entity\ProductType $productType
     *
     * @return Product
     */
    public function setProductType(ProductType $productType = null)
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * Get productType
     *
     * @return \DTR\DTRBundle\Entity\ProductType
     */
    public function getProductType()
    {
        return $this->productType;
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
}
