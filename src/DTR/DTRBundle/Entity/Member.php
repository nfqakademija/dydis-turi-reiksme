<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DTR\DTRBundle\Entity\MemberRepository")
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
     * @ORM\OneToOne(targetEntity="User")
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
    private $debt;


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
     * Set debt
     *
     * @param float $debt
     *
     * @return Member
     */
    public function setDebt($debt)
    {
        $this->debt = $debt;

        return $this;
    }

    /**
     * Get debt
     *
     * @return float
     */
    public function getDebt()
    {
        return $this->debt;
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
        $item->setMember(null);
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
