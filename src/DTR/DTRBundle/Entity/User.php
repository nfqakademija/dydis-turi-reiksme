<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="user")
     */
    protected $events;

    /**
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="user")
     */
    protected $participations;

    public function __construct() {
        parent::__construct();

        $this->events = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }
    
    public function __toString() {
        return $this->getUsername();
    }

    /**
     * Add event
     *
     * @param \DTR\DTRBundle\Entity\Event $event
     *
     * @return User
     */
    public function addEvent(Event $event) {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \DTR\DTRBundle\Entity\Event $event
     */
    public function removeEvent(Event $event) {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents() {
        return $this->events;
    }


    /**
     * Add participation
     *
     * @param \DTR\DTRBundle\Entity\Participation $participation
     *
     * @return User
     */
    public function addParticipation(\DTR\DTRBundle\Entity\Participation $participation)
    {
        $this->participations[] = $participation;

        return $this;
    }

    /**
     * Remove participation
     *
     * @param \DTR\DTRBundle\Entity\Participation $participation
     */
    public function removeParticipation(\DTR\DTRBundle\Entity\Participation $participation)
    {
        $this->participations->removeElement($participation);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }
}
