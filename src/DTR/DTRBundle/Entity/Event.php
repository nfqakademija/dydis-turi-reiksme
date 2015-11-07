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
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=6)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="guest_limit", type="integer")
     */
    private $guestLimit;

    /**
     * @var float
     *
     * @ORM\Column(name="funds_limit", type="float")
     */
    private $fundsLimit;

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
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
     * Set guestLimit
     *
     * @param integer $guestLimit
     *
     * @return Event
     */
    public function setGuestLimit($guestLimit)
    {
        $this->guestLimit = $guestLimit;

        return $this;
    }

    /**
     * Get guestLimit
     *
     * @return integer
     */
    public function getGuestLimit()
    {
        return $this->guestLimit;
    }

    /**
     * Set fundsLimit
     *
     * @param float $fundsLimit
     *
     * @return Event
     */
    public function setFundsLimit($fundsLimit)
    {
        $this->fundsLimit = $fundsLimit;

        return $this;
    }

    /**
     * Get fundsLimit
     *
     * @return float
     */
    public function getFundsLimit()
    {
        return $this->fundsLimit;
    }
}

