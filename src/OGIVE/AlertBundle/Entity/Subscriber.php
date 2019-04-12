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
class Subscriber {

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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="notification_type", type="integer", length=255)
     */
    private $notificationType = 3;

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
     * @var integer
     *
     * @ORM\Column(name="expired_state", type="integer")
     */
    protected $expiredState = 0;

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
     * @ORM\OneToMany(targetEntity="HistoricalSubscriberSubscription", mappedBy="subscriber", cascade={"remove", "persist"})
     */
    private $historicalSubscriberSubscriptions;

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
     * @var \DateTime
     *
     * @ORM\Column(name="last_subscription_date", type="datetime")
     */
    private $lastSubscriptionDate;

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
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Subscriber
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Subscriber
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Subscriber
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * Set notificationType
     *
     * @param integer $notificationType
     *
     * @return Subscriber
     */
    public function setNotificationType($notificationType) {
        $this->notificationType = $notificationType;

        return $this;
    }

    /**
     * Get notificationType
     *
     * @return integer
     */
    public function getNotificationType() {
        return $this->notificationType;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Subscriber
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return Subscriber
     */
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set expiredState
     *
     * @param integer $expiredState
     *
     * @return Subscriber
     */
    public function setExpiredState($expiredState) {
        $this->expiredState = $expiredState;

        return $this;
    }

    /**
     * Get expiredState
     *
     * @return integer
     */
    public function getExpiredState() {
        return $this->expiredState;
    }

    /**
     * Set entreprise
     *
     * @param \OGIVE\AlertBundle\Entity\Entreprise $entreprise
     *
     * @return Subscriber
     */
    public function setEntreprise($entreprise) {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return \OGIVE\AlertBundle\Entity\Entreprise
     */
    public function getEntreprise() {
        return $this->entreprise;
    }

    /**
     * Set subscription
     *
     * @param \OGIVE\AlertBundle\Entity\Subscription $subscription
     *
     * @return Subscriber
     */
    public function setSubscription(\OGIVE\AlertBundle\Entity\Subscription $subscription = null) {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \OGIVE\AlertBundle\Entity\Subscription
     */
    public function getSubscription() {
        return $this->subscription;
    }

    /**
     * Add historicalSubscriberSubscription
     *
     * @param \OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription $historicalSubscriberSubscription
     * @return Subscriber
     */
    public function addHistoricalSubscriberSubscription(\OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription $historicalSubscriberSubscription) {
        $this->historicalSubscriberSubscriptions[] = $historicalSubscriberSubscription;
        return $this;
    }

    /**
     * Get historicalSubscriberSubscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoricalSubscriberSubscriptions() {
        return $this->historicalSubscriberSubscriptions;
    }

    /**
     * Set historicalSubscriberSubscriptions
     *
     * @param \Doctrine\Common\Collections\Collection $historicalSubscriberSubscriptions
     * @return Subscriber
     */
    public function setHistoricalSubscriberSubscriptions(\Doctrine\Common\Collections\Collection $historicalSubscriberSubscriptions = null) {
        $this->historicalSubscriberSubscriptions = $historicalSubscriberSubscriptions;

        return $this;
    }

    /**
     * Remove historicalSubscriberSubscriptions
     *
     * @param \OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription $historicalSubscriberSubscription
     * @return Subscriber
     */
    public function removeHistoricalSubscriberSubscription(\OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription $historicalSubscriberSubscription) {
        $this->historicalSubscriberSubscriptions->removeElement($historicalSubscriberSubscription);
        return $this;
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
    public function setCreateDate($createDate) {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate() {
        return $this->createDate;
    }

    /**
     * Set lastUpdateDate
     *
     * @param \DateTime $lastUpdateDate
     *
     * @return Subscriber
     */
    public function setLastUpdateDate($lastUpdateDate) {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    /**
     * Get lastUpdateDate
     *
     * @return \DateTime
     */
    public function getLastUpdateDate() {
        return $this->lastUpdateDate;
    }

    /**
     * Set lastSubscriptionDate
     *
     * @param \DateTime $lastSubscriptionDate
     *
     * @return Subscriber
     */
    public function setLastSubscriptionDate($lastSubscriptionDate) {
        $this->lastSubscriptionDate = $lastSubscriptionDate;

        return $this;
    }

    /**
     * Get lastSubscriptionDate
     *
     * @return \DateTime
     */
    public function getLastSubscriptionDate() {
        return $this->lastSubscriptionDate;
    }

    /**
     * @ORM\PreUpdate() 
     */
    public function preUpdate() {
        $this->lastUpdateDate = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist() 
     */
    public function prePersist() {
        $this->createDate = new \DateTime('now');
        $this->lastUpdateDate = new \DateTime('now');
        $this->status = 1;
        $this->expiredState = 0;
    }

    public function getSubscriptionCostAndValidity() {
        $costAndValidity = "";
        if ($this->getSubscription()->getPeriodicity() === 1) {
            $costAndValidity = $this->getSubscription()->getPrice() . " " . $this->getSubscription()->getCurrency() . ", validite = 1 an.";
        } elseif ($this->getSubscription()->getPeriodicity() === 2) {
            $costAndValidity = $this->getSubscription()->getPrice() . " " . $this->getSubscription()->getCurrency() . ", validite = 6 mois.";
        } elseif ($this->getSubscription()->getPeriodicity() === 3) {
            $costAndValidity = $this->getSubscription()->getPrice() . " " . $this->getSubscription()->getCurrency() . ", validite = 3 mois.";
        } elseif ($this->getSubscription()->getPeriodicity() === 4) {
            $costAndValidity = $this->getSubscription()->getPrice() . " " . $this->getSubscription()->getCurrency() . ", validite = 1 mois.";
        } elseif ($this->getSubscription()->getPeriodicity() === 5) {
            $costAndValidity = $this->getSubscription()->getPrice() . " " . $this->getSubscription()->getCurrency() . ", validite = 2 semaine.";
        }
        return $costAndValidity;
    }

}
