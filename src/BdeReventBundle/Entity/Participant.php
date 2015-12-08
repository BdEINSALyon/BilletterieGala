<?php

namespace BdeReventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BdeReventBundle\Entity\ParticipantRepository")
 */
class Participant
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
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName = "";

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName = "";

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="BdeReventBundle\Entity\Type")
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="used", type="boolean")
     */
    private $used = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BdeReventBundle\Entity\Participant", mappedBy="invitedBy")
     */
    private $guests;

    /**
     * @var int
     *
     * @ORM\Column(name="weezevent_invites", type="integer")
     */
    private $guests_by_weezevent = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $last_login;

    /**
     * @var Participant
     *
     * @ORM\ManyToOne(targetEntity="BdeReventBundle\Entity\Participant", inversedBy="guests")
     */
    private $invitedBy;

    public function __construct() {
        $this->guests = new ArrayCollection();
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
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Participant
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Participant
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Participant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get type
     *
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param Type $type
     *
     * @return Participant
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set used
     *
     * @param boolean $used
     *
     * @return Participant
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get invitedById
     *
     * @return Participant|null
     */
    public function getInvitedBy()
    {
        return $this->invitedBy;
    }

    /**
     * Set invitedById
     *
     * @param Participant $participant
     *
     * @return Participant
     */
    public function setInvitedBy($participant)
    {
        $this->invitedBy = $participant;

        return $this;
    }

    public function __toString(){
        return $this->firstName.' '.strtoupper($this->lastName);
    }

    /**
     * @return int
     */
    public function getGuestsByWeezevent()
    {
        return $this->guests_by_weezevent;
    }

    /**
     * @param int $guests_by_weezevent
     */
    public function setGuestsByWeezevent($guests_by_weezevent)
    {
        $this->guests_by_weezevent = $guests_by_weezevent;
    }

    /**
     * @return ArrayCollection
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     * @param \DateTime $last_login
     */
    public function setLastLogin($last_login)
    {
        $this->last_login = $last_login;
    }
}

