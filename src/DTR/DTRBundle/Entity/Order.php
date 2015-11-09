<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity
 */
class Order
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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Item")
     * @ORM\JoinTable(
     *      name="orders_items",
     *      joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id", unique=true)})
     */
    private $items;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_paid", type="boolean")
     */
    private $isPaid;

    /**
     * @var \DTR\DTRBundle\Entity\Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="orders")
     * @ORM\JoinColumn(name="event_hash", referencedColumnName="hash")
     */
    private $event;

    /**
     * @var \DTR\DTRBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="oders")
     */
    private $user;

    /**
     * Public constructor
     */
    public function __construct()
    {
        $this->isPaid = false;
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Order
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
     * Set isPaid
     *
     * @param boolean $isPaid
     *
     * @return Order
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid
     *
     * @return boolean
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * Add item
     *
     * @param \DTR\DTRBundle\Entity\Item $item
     *
     * @return Order
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \DTR\DTRBundle\Entity\Item $item
     */
    public function removeItem(Item $item)
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

    /**
     * Set event
     *
     * @param \DTR\DTRBundle\Entity\Event $event
     *
     * @return Order
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \DTR\DTRBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set user
     *
     * @param \DTR\DTRBundle\Entity\User $user
     *
     * @return Order
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \DTR\DTRBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
