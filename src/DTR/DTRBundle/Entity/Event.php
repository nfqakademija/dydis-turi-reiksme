<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Event
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
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="limit_guests", type="integer")
     */
    private $limitGuests;

    /**
     * @var float
     *
     * @ORM\Column(name="limit_price", type="float")
     */
    private $limitPrice;


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
     * @return Event
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
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Event
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set limitGuests
     *
     * @param integer $limitGuests
     *
     * @return Event
     */
    public function setLimitGuests($limitGuests)
    {
        $this->limitGuests = $limitGuests;

        return $this;
    }

    /**
     * Get limitGuests
     *
     * @return integer
     */
    public function getLimitGuests()
    {
        return $this->limitGuests;
    }

    /**
     * Set limitPrice
     *
     * @param float $limitPrice
     *
     * @return Event
     */
    public function setLimitPrice($limitPrice)
    {
        $this->limitPrice = $limitPrice;

        return $this;
    }

    /**
     * Get limitPrice
     *
     * @return float
     */
    public function getLimitPrice()
    {
        return $this->limitPrice;
    }
}

