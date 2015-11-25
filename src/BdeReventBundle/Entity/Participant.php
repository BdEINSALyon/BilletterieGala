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
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

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
    private $used;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BdeReventBundle\Entity\Participant", mappedBy="invitedBy")
     */
    private $guests;

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
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
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
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
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
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Get type
     *
     * @return Type
     */
    public function getType()
    {
        return $this->type;
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
     * Get used
     *
     * @return boolean
     */
    public function getUsed()
    {
        return $this->used;
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

    /**
     * Get invitedById
     *
     * @return Participant|null
     */
    public function getInvitedBy()
    {
        return $this->invitedBy;
    }

    public function __toString(){
        return $this->firstName.' '.strtoupper($this->lastName);
    }
}

