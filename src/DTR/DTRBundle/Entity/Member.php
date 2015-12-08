<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DTR\DTRBundle\Repository\MemberRepository")
 */
class Member
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
     * @ORM\Column(name="is_host", type="boolean")
     */
    private $is_host = false;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="members")
     * @ORM\JoinColumn(name="event_hash", referencedColumnName="hash")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="member")
     */
    private $items;

    /**
     * @var float
     *
     * @ORM\Column(name="debt", type="float")
     */
    private $debt = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="total_price", type="float")
     */
    private $total_price = 0.00;


    /**
     * Constructor
     */
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
     * @return string
     */
    public function getName()
    {
        return $this->user->getUsername();
    }

    /**
     * Set member as host
     *
     * @return Member
     */
    public function setHost()
    {
        $this->is_host = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHost()
    {
        return $this->is_host;
    }

    /**
     * Increase debt for current member.
     *
     * @param float
     * @return Member
     */
    public function increaseDebt($amount)
    {
        $this->debt += $amount;
        $this->event->increaseTotalDebt($amount);

        return $this;
    }

    /**
     * Decrease debt for current member.
     *
     * @param float
     * @return Member
     */
    public function decreaseDebt($amount)
    {
        $this->debt -= $amount;
        $this->event->decreaseTotalDebt($amount);

        return $this;
    }

    /**
     * Get debt of current member.
     *
     * @return float
     */
    public function getDebt()
    {
        $this->debt = $this->total_price;
        return $this->debt;
    }

    public function removeDebt() {
        $this->debt = 0.0;
    }

    /**
     * Increase total price for current member.
     *
     * @param $amount
     * @return Member
     */
    public function increaseTotalPrice($amount)
    {
        $this->total_price += $amount;
        $this->event->increaseTotalPrice($amount);

        return $this;
    }

    /**
     * Decrease total price for current member.
     *
     * @param $amount
     * @return Member
     */
    public function decreaseTotalPrice($amount)
    {
        $this->total_price -= $amount;
        $this->event->decreaseTotalPrice($amount);

        return $this;
    }

    /**
     * Get total price of current member.
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function getPriceWithQuantity()
    {
        $totalPrice = 0.0;
        foreach ($this->items as $item) {
            $productPrice = $item->getProduct()->getPrice() * $item->getQuantity();
            $totalPrice += $productPrice;
        }
        $this->total_price = $totalPrice;
        return $this->total_price;
    }

    /**
     * Set event
     *
     * @param \DTR\DTRBundle\Entity\Event $event
     *
     * @return Member
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
     * Add item
     *
     * @param \DTR\DTRBundle\Entity\Item $item
     *
     * @return Member
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
        $item->setMember($this);

        $price = $item->getProduct()->getPrice();

        $this->increaseDebt($price);
        $this->increaseTotalPrice($price);

        return $this;
    }

    /**
     * Remove item
     *
     * @param \DTR\DTRBundle\Entity\Item $item
     * @return $this
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
        $item->setMember(null);

        $price = $item->getProduct()->getPrice();

        $this->decreaseDebt($price);
        $this->decreaseTotalPrice($price);

        return $this;
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
     * @return int
     */
    public function getNumberOfItems()
    {
        $totalItems = 0;
        foreach ($this->items as $item) {
            $totalItems += $item->getQuantity();
        }

        return $totalItems;
    }

    /**
     * Set user
     *
     * @param \DTR\DTRBundle\Entity\User $user
     *
     * @return Member
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
