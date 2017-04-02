<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricalAlertSubscriber
 *
 * @ORM\Table(name="historical_alert_subscriber")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\HistoricalAlertSubscriberRepository")
 * @ORM\HasLifecycleCallbacks
 */
class HistoricalAlertSubscriber
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
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var \Subscriber
     *
     * @ORM\ManyToOne(targetEntity="Subscriber")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscriber", referencedColumnName="id")
     * })
     */
    private $subscriber;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;
    
    /**
     * @var string
     *
     * @ORM\Column(name="procedureType", type="string", length=255, nullable=true)
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
     * @return HistoricalAlertSubscriber
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
     * Set subscriber
     *
     * @param \OGIVE\AlertBundle\Entity\Subscriber $subscriber
     * @return HistoricalAlertSubscriber
     */
    public function setSubscriber(\OGIVE\AlertBundle\Entity\Subscriber $subscriber = null)
    {
        $this->subscriber = $subscriber;
        return $this;
    }
    
    /**
    * Get subscriber
    *
    * @return \OGIVE\AlertBundle\Entity\Subscriber 
    */
    public function getSubscriber()
    {
        return $this->subscriber;
    }
    
    /**
    * Set subject
    *
    * @param string $subject
    *
    * @return HistoricalAlertSubscriber
    */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
    * Set message
    *
    * @param string $message
    *
    * @return HistoricalAlertSubscriber
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
     * @return HistoricalAlertSubscriber
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
     * @return HistoricalAlertSubscriber
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
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return HistoricalAlertSubscriber
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
     * @return HistoricalAlertSubscriber
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

