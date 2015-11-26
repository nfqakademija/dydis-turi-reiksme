<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DTR\DTRBundle\Entity\ItemRepository")
 */
class Item
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
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Pasirinktas kiekis negali būti mažesnis už 1.")
     */
    private $quantity = 1;

    /**
     * @var Product
     *
     * @ORM\OneToOne(targetEntity="Product")
     */
    private $product;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set order
     *
     * @param \DTR\DTRBundle\Entity\Order $order
     *
     * @return Item
     */
    public function setOrder(Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \DTR\DTRBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \DTR\DTRBundle\Entity\Product $product
     *
     * @return Item
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \DTR\DTRBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
