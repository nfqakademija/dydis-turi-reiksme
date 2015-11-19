<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Event {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank(message="Pavadinimo laukas negali būti tuščias.")
     * @Assert\Length(
     *      min=3,
     *      minMessage="Pavadinimas negali būti trumpesnis nei 3 raidės.",
     *      max=255,
     *      maxMessage="Pavadinimas negali būti ilgesnis nei 255 raidės.")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="guest_limit", type="integer")
     *
     * @Assert\Range(
     *      min=1,
     *      minMessage="Svečių limitas negali būti mažesnis už 1.",
     *      max=60,
     *      maxMessage="Svečių skaičius negali būti didesnis nei 60.")
     */
    private $guestLimit;

    /*
     * @var integer
     * 
     * @ORM\Column(name="guest_amount", type="integer")
     */
    private $guestAmount;
    
    /**
     * @var float
     *
     * @ORM\Column(name="funds_limit", type="float")
     *
     * @Assert\GreaterThanOrEqual(
     *      value=5.00,
     *      message="Pinigų sumos limitas negali būti mažesnis už 5.00.")
     */
    private $fundsLimit;

    /**
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="event")
     */
    protected $participations;

    /**
     * Public constructor
     */
    public function __construct() {
        $this->participations = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Event
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set guestLimit
     *
     * @param integer $guestLimit
     *
     * @return Event
     */
    public function setGuestLimit($guestLimit) {
        $this->guestLimit = $guestLimit;

        return $this;
    }

    /**
     * Get guestLimit
     *
     * @return integer
     */
    public function getGuestLimit() {
        return $this->guestLimit;
    }

    /**
     * Set fundsLimit
     *
     * @param float $fundsLimit
     *
     * @return Event
     */
    public function setFundsLimit($fundsLimit) {
        $this->fundsLimit = $fundsLimit;

        return $this;
    }

    /**
     * Get fundsLimit
     *
     * @return float
     */
    public function getFundsLimit() {
        return $this->fundsLimit;
    }

    /**
     * Add member
     *
     * @param \DTR\DTRBundle\Entity\User $member
     *
     * @return Event
     */
    public function addMember(User $member) {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \DTR\DTRBundle\Entity\User $member
     */
    public function removeMember(User $member) {
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers() {
        return $this->members;
    }


    /**
     * Add participation
     *
     * @param \DTR\DTRBundle\Entity\Participation $participation
     *
     * @return Event
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

    /**
     * Set user
     *
     * @param \DTR\DTRBundle\Entity\User $user
     *
     * @return Event
     */
    public function setUser(\DTR\DTRBundle\Entity\User $user = null)
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
