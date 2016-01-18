<?php

namespace BdeReventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WaitingTicket
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BdeReventBundle\Entity\WaitingTicketRepository")
 */
class WaitingTicket
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
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var Participant
     *
     * @ORM\OneToOne(targetEntity="BdeReventBundle\Entity\Participant")
     */
    private $participant;

    /**
     * @var String
     *
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @var number
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @return number
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param number $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return WaitingTicket
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get participantId
     *
     * @return integer
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set participantId
     *
     * @param integer $participantId
     *
     * @return WaitingTicket
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return WaitingTicket
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }
}

