<?php

namespace BdeReventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Type
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
     * @ORM\Column(name="name", type="string", length=70)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="can_invite", type="boolean")
     */
    private $canInvite;


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
     * Get canInvite
     *
     * @return boolean
     */
    public function getCanInvite()
    {
        return $this->canInvite;
    }

    /**
     * Set canInvite
     *
     * @param boolean $canInvite
     *
     * @return Type
     */
    public function setCanInvite($canInvite)
    {
        $this->canInvite = $canInvite;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Type
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}

