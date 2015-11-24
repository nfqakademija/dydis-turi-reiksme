<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="`order`")
 * @ORM\Entity(repositoryClass="DTR\DTRBundle\Entity\OrderRepository")
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
     * @var float
     *
     * @ORM\Column(name="debt", type="float")
     */
    private $debt;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User")
     */
    private $user;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="orders")
     * @ORM\JoinColumn(name="event_hash", referencedColumnName="hash")
     */
    private $event;


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
     * Set debt
     *
     * @param float $debt
     *
     * @return Order
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

    /**
     * Set event
     *
     * @param \DTR\DTRBundle\Entity\Event $event
     *
     * @return Order
     */
    public function setEvent(\DTR\DTRBundle\Entity\Event $event = null)
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
}
