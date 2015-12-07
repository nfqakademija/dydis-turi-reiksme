<?php

namespace DTR\DTRBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DTR\DTRBundle\Repository\EventRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Member", mappedBy="event")
     */
    private $members;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="date")
     *
     * @Assert\GreaterThan("now")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="payment_made", type="boolean")
     */
    private $payment_made = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="guest_limit", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min=1,
     *      minMessage="Svečių limitas negali būti mažesnis už 1.",
     *      max=60,
     *      maxMessage="Svečių skaičius negali būti didesnis nei 60.")
     */
    private $guestLimit;

    /**
     * @var float
     *
     * @ORM\Column(name="funds_limit", type="float")
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *      value=5.00,
     *      message="Pinigų sumos limitas negali būti mažesnis už 5.00.")
     */
    private $fundsLimit;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shop")
     * @ORM\JoinTable(
     *      name="events_shops",
     *      joinColumns={@ORM\JoinColumn(name="event_hash", referencedColumnName="hash")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="id")})
     */
    private $shops;

    /**
     * @var float
     *
     * @ORM\Column(name="total_price", type="float")
     */
    private $total_price = 0.00;

    /**
     * @var float
     *
     * @ORM\Column(name="total_debt", type="float")
     */
    private $total_debt = 0.00;

    /**
     * Public constructor
     */
    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->date = new DateTime();
        $this->shops = new ArrayCollection();
    }

    /**
     * @param LifecycleEventArgs $postPersist
     *
     * @ORM\PostPersist
     */
    public function setHash(LifecycleEventArgs $postPersist)
    {
        $event = $postPersist->getObject();
        $hash = $event->getHash();

        $this->hash = substr($hash, 0, 6);
    }

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

    /**
     * Add member
     *
     * @param \DTR\DTRBundle\Entity\Member $member
     *
     * @return Event
     */
    public function addMember(Member $member)
    {
        $this->members[] = $member;
        $member->setEvent($this);

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
        $member->setEvent(null);
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

    /**
<<<<<<< Updated upstream
     * Get construct of host member entity and guest member entities.
=======
     * @return int
     */
    public function getMembersCount()
    {
        return $this->members->count();
    }

    /**
     * Set totalPrice
>>>>>>> Stashed changes
     *
     * @return array
     */
    public function getHostAndGuests()
    {
        $guests = new ArrayCollection();
        $host = null;

        foreach($this->members as $member)
        {
            if($member->isHost()) {
                $host = $member;
            }
            else {
                $guests[] = $member;
            }
        }

        return [ $host, $guests ];
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getGuests()
    {
        return $this->members->filter(function(Member $member) {
            return !$member->isHost();
        });
    }

    /**
     * Increase total price.
     *
     * @param $amount
     * @return Event
     */
    public function increaseTotalPrice($amount)
    {
        $this->total_price += $amount;

        return $this;
    }

    /**
     * Decrease total price.
     *
     * @param $amount
     * @return Event
     */
    public function decreaseTotalPrice($amount)
    {
        $this->total_price -= $amount;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    /**
     * Increase total debt.
     *
     * @param $amount
     * @return Event
     */
    public function increaseTotalDebt($amount)
    {
        $this->total_debt += $amount;

        return $this;
    }

    /**
     * Decrease total debt.
     *
     * @param $amount
     * @return Event
     */
    public function decreaseTotalDebt($amount)
    {
        $this->total_debt -= $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalDebt()
    {
        return $this->total_debt;
    }

    /**
     * Set date
     *
     * @param DateTime $date
     *
     * @return Event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        $current_date = new DateTime();

        return $this->date > $current_date;
    }

    public function isPaymentMade()
    {
        return $this->payment_made;
    }

    public function setPaymentMade($is_made)
    {
        $this->payment_made = $is_made;

        return $this;
    }

    /**
     * Get paymentMade
     *
     * @return boolean
     */
    public function getPaymentMade()
    {
        return $this->payment_made;
    }

    /**
     * Add shop
     *
     * @param \DTR\DTRBundle\Entity\Shop $shop
     *
     * @return Event
     */
    public function addShop(Shop $shop)
    {
        $this->shops[] = $shop;

        return $this;
    }

    /**
     * Remove shop
     *
     * @param \DTR\DTRBundle\Entity\Shop $shop
     */
    public function removeShop(Shop $shop)
    {
        $this->shops->removeElement($shop);
    }

    /**
     * Get shops
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShops()
    {
        return $this->shops;
    }

    /**
     * @return bool
     */
    public function isFull()
    {
        $members_count = $this->members->count() - 1;

        return $this->guestLimit == $members_count;
    }
}
