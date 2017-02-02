<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricalAlertEntreprise
 *
 * @ORM\Table(name="historical_alert_entreprise")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\HistoricalAlertEntrepriseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class HistoricalAlertEntreprise
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
     * @var int 
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var \Entreprise
     *
     * @ORM\ManyToOne(targetEntity="Entreprise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entreprise", referencedColumnName="id")
     * })
     */
    private $entreprise;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    
    /**
     * @var string
     *
     * @ORM\Column(name="procedureType", type="string", length=255)
     */
    private $procedureType;
    
    /**
     * @var string
     *
     * @ORM\Column(name="alertType", type="string", length=255)
     */
    private $alertType;
    
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
     * Set status
     *
     * @param integer $status
     *
     * @return HistoricalAlertEntreprise
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
    * Set message
    *
    * @param string $message
    *
    * @return HistoricalAlertEntreprise
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
    
    /**
     * Set alertType
     *
     * @param string $alertType
     *
     * @return HistoricalAlertEntreprise
     */
    public function setAlertType($alertType)
    {
        $this->alertType = $alertType;

        return $this;
    }

    /**
     * Get alertType
     *
     * @return string
     */
    public function getAlertType()
    {
        return $this->alertType;
    }
    
    /**
     * Set procedureType
     *
     * @param string $procedureType
     *
     * @return HistoricalAlertEntreprise
     */
    public function setProcedureType($procedureType)
    {
        $this->procedureType = $procedureType;

        return $this;
    }

    /**
     * Get procedureType
     *
     * @return string
     */
    public function getProcedureType()
    {
        return $this->procedureType;
    }
    
    
    /**
     * Set entreprise
     *
     * @param \OGIVE\AlertBundle\Entity\Entreprise $entreprise
     * @return HistoricalAlertEntreprise
     */
    public function setEntreprise(\OGIVE\AlertBundle\Entity\Entreprise $entreprise= null)
    {
        $this->entreprise= $entreprise;
        return $this;
    }
    
    /**
    * Get entreprise
    *
    * @return \OGIVE\AlertBundle\Entity\Entreprise 
    */
    public function getEntreprise()
    {
        return $this->entreprise;
    }
    
    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return HistoricalAlertEntreprise
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
     * @return HistoricalAlertEntreprise
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
        $this->lastUpdateDate = new \DateTime();
    }

    /**
     * @ORM\PrePersist() 
     */
    public function prePersist()
    {
        $this->createDate = new \DateTime();
        $this->lastUpdateDate = new \DateTime();
        $this->status = 1;
    }
}

