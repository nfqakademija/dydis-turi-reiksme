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
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="items")
     */
    private $member;


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

    /**
     * Set member
     *
     * @param \DTR\DTRBundle\Entity\Member $member
     *
     * @return Item
     */
    public function setMember(Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \DTR\DTRBundle\Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }
}
