<?php

namespace DTR\DTRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shop
 *
 * @ORM\Table(name="shop")
 * @ORM\Entity
 */
class Shop
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
     * @var integer
     *
     * @ORM\Column(name="shoptype_id", type="integer")
     */
    private $shoptypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * Set shoptypeId
     *
     * @param integer $shoptypeId
     *
     * @return Shop
     */
    public function setShoptypeId($shoptypeId)
    {
        $this->shoptypeId = $shoptypeId;

        return $this;
    }

    /**
     * Get shoptypeId
     *
     * @return integer
     */
    public function getShoptypeId()
    {
        return $this->shoptypeId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Shop
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
}

