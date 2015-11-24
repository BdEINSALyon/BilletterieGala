<?php

namespace BdeReventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mail
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BdeReventBundle\Entity\MailRepository")
 */
class Mail
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
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="BdeReventBundle\Entity\Type")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=512)
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;


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
     * Set object
     *
     * @param string $object
     *
     * @return Mail
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Mail
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}

