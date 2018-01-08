<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Owner
 *
 * @ORM\Table(name="owner")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\OwnerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Owner
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=255)
     */
    private $numero;
    
    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var int
     *
     * @ORM\Column(name="state", type="integer", nullable=true)
     */
    private $state;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_date", type="datetime")
     */
    private $lastUpdateDate;
    
    /**
     * Constructor
     */
    public function __construct() {
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Owner
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
     * Set numero
     *
     * @param string $numero
     *
     * @return Owner
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }
    
    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Owner
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set state
     *
     * @param integer $state
     *
     * @return Owner
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return int
     */
    public function getSate()
    {
        return $this->state;
    }
    
    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Owner
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }
    
    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
    
    /**
     * Set lastUpdateDate
     *
     * @param \DateTime $lastUpdateDate
     *
     * @return Owner
     */
    public function setLastUpdateDate($lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    /**
     * Get lastUpdateDate
     *
     * @return \DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }
    
    /**
     * @ORM\PreUpdate() 
     */
    public function preUpdate()
    {
        $this->lastUpdateDate = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist() 
     */
    public function prePersist()
    {
        $this->createDate = new \DateTime('now');
        $this->lastUpdateDate = new \DateTime('now');
        $this->status = 1;
    }
}

