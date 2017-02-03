<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscriber
 *
 * @ORM\Table(name="subscriber")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\SubscriberRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Subscriber
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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", length=255)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    protected $state;
    
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
     * @var \Subscription
     *
     * @ORM\ManyToOne(targetEntity="Subscription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription", referencedColumnName="id")
     * })
     */
    private $subscription;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="HistoricalAlertSubscriber", mappedBy="subscriber", cascade={"remove", "persist"})
     */
    private $historicalAlertSubscribers;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SpecialFollowUp", mappedBy="subscribers", cascade={"persist"})
     * 
     */
    private $specialFollowUps;
    
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
        $this->state = 0;
        $this->specialFollowUps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->historicalAlertSubscribers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Subscriber
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
     * Set email
     *
     * @param string $email
     *
     * @return Subscriber
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Subscriber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Subscriber
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
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
     * @return Subscriber
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Set entreprise
     *
     * @param \OGIVE\AlertBundle\Entity\Entrprise $entreprise
     *
     * @return Subscriber
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;

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
     * Set subscription
     *
     * @param \OGIVE\AlertBundle\Entity\Subscription $subscription
     *
     * @return Subscriber
     */
    public function setSubscription(\OGIVE\AlertBundle\Entity\Subscription $subscription=null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \OGIVE\AlertBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }
    
    /**
     * Add historicalAlertSubscriber
     *
     * @param \OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber $historicalAlertSubscriber
     * @return Subscriber
     */
    public function addHistoricalAlertSubscriber(\OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber $historicalAlertSubscriber) {
        $this->historicalAlertSubscribers[] = $historicalAlertSubscriber;
        return $this;
    }

    /**
     * Get historicalAlertSubscribers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoricalAlertSubscribers() {
        return $this->historicalAlertSubscribers;
    }

    /**
     * Set historicalAlertSubscribers
     *
     * @param \Doctrine\Common\Collections\Collection $historicalAlertSubscribers
     * @return Subscriber
     */
    public function setHistoricalAlertSubscribers(\Doctrine\Common\Collections\Collection $historicalAlertSubscribers = null) {
        $this->historicalAlertSubscribers = $historicalAlertSubscribers;

        return $this;
    }

    /**
     * Remove historicalAlertSubscribers
     *
     * @param \OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber $historicalAlertSubscriber
     * @return Subscriber
     */
    public function removeHistoricalAlertSubscriber(\OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber $historicalAlertSubscriber) {
        $this->historicalAlertSubscribers->removeElement($historicalAlertSubscriber);
        return $this;
    }
    
    
    /**
     * Add specialFollowUp
     *
     * @param \OGIVE\AlertBundle\Entity\SpecialFollowUp $specialFollowUp
     * @return Subscriber
     */
    public function addSpecialFollowUp(\OGIVE\AlertBundle\Entity\SpecialFollowUp $specialFollowUp) {
        $this->specialFollowUps[] = $specialFollowUp;
        return $this;
    }

    /**
     * Get specialFollowUps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecialFollowUps() {
        return $this->specialFollowUps;
    }

    /**
     * Set specialFollowUps
     *
     * @param \Doctrine\Common\Collections\Collection $specialFollowUps
     * @return Subscriber
     */
    public function setSpecialFollowUps(\Doctrine\Common\Collections\Collection $specialFollowUps = null) {
        $this->specialFollowUps = $specialFollowUps;

        return $this;
    }

    /**
     * Remove specialFollowUps
     *
     * @param \OGIVE\AlertBundle\Entity\SpecialFollowUp $specialFollowUp
     * @return Subscriber
     */
    public function removeSpecialFollowUp(\OGIVE\AlertBundle\Entity\SpecialFollowUp $specialFollowUp) {
        $this->entreprises->removeElement($specialFollowUp);
        return $this;
    }
    
    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Subscriber
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
     * @return Subscriber
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

