<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Member", mappedBy="user")
     */
    protected $members;


    /**
     * Public constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->events = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    /**
     * Add event
     *
     * @param \DTR\DTRBundle\Entity\Event $event
     *
     * @return User
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \DTR\DTRBundle\Entity\Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add member
     *
     * @param \DTR\DTRBundle\Entity\Member $member
     *
     * @return User
     */
    public function addMember(Member $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \DTR\DTRBundle\Entity\Member $member
     */
    public function removeMember(Member $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers()
    {
        return $this->members;
    }
}
