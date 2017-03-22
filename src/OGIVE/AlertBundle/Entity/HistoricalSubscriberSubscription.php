<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricalSubscriberSubscription
 *
 * @ORM\Table(name="historical_subscriber_subscription")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\HistoricalSubscriberSubscriptionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class HistoricalSubscriberSubscription {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", length=255)
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
     * @var \Subscription
     *
     * @ORM\ManyToOne(targetEntity="Subscription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription", referencedColumnName="id")
     * })
     */
    private $subscription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="subscription_date", type="datetime")
     */
    private $subscriptionDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime")
     */
    private $expirationDate;

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
    public function getId() {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return HistoricalSubscriberSubscription
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
     * Set subscriber
     *
     * @param \OGIVE\AlertBundle\Entity\Subscriber $subscriber
     *
     * @return HistoricalSubscriberSubscription
     */
    public function setSubscriber(\OGIVE\AlertBundle\Entity\Subscriber $subscriber = null) {
        $this->subscriber = $subscriber;

        return $this;
    }

    /**
     * Get subscriber
     *
     * @return \OGIVE\AlertBundle\Entity\Subscriber
     */
    public function getSubscriber() {
        return $this->subscriber;
    }

    /**
     * Set subscription
     *
     * @param \OGIVE\AlertBundle\Entity\Subscription $subscription
     *
     * @return HistoricalSubscriberSubscription
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
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return HistoricalSubscriberSubscription
     */
    public function setExpirationDate($expirationDate) {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate() {
        return $this->expirationDate;
    }

    /**
     * Set subscriptionDate
     *
     * @param \DateTime $subscriptionDate
     *
     * @return HistoricalSubscriberSubscription
     */
    public function setSubscriptionDate($subscriptionDate) {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    /**
     * Get subscriptionDate
     *
     * @return \DateTime
     */
    public function getSubscriptionDate() {
        return $this->subscriptionDate;
    }

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
     * @return HistoricalSubscriberSubscription
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
    }

    public function setSubscriptionDateAndExpirationDate($subscriptionDate) {

        if ($this->subscription) {
            $this->subscriptionDate = $subscriptionDate;
            $cd = strtotime($subscriptionDate->format('Y-m-d H:i:s'));
            switch ($this->subscription->getPeriodicity()) {
                case 1 :
                    $this->expirationDate = new \DateTime(date('Y-m-d H:i:s', mktime(date('H', $cd), date('i', $cd), date('s', $cd), date('m', $cd), date('d', $cd), date('Y', $cd) + 1)));
                    break;
                case 2 :
                    $this->expirationDate = new \DateTime(date('Y-m-d H:i:s', mktime(date('H', $cd), date('i', $cd), date('s', $cd), date('m', $cd) + 6, date('d', $cd), date('Y', $cd))));
                    break;
                case 3 :
                    $this->expirationDate = new \DateTime(date('Y-m-d H:i:s', mktime(date('H', $cd), date('i', $cd), date('s', $cd), date('m', $cd) + 3, date('d', $cd), date('Y', $cd))));
                    break;
                case 4 :
                    $this->expirationDate = new \DateTime(date('Y-m-d H:i:s', mktime(date('H', $cd), date('i', $cd), date('s', $cd), date('m', $cd) + 1, date('d', $cd), date('Y', $cd))));
                    break;
                case 5:
                    $this->expirationDate = new \DateTime(date('Y-m-d H:i:s', mktime(date('H', $cd), date('i', $cd), date('s', $cd), date('m', $cd), date('d', $cd) + 7, date('Y', $cd))));
                    break;
                default :
                    $this->expirationDate = null;
                    break;
            }
            
            //$this->expirationDate = new \DateTime(date('Y-m-d H:i:s', mktime(date('H', $cd), date('i', $cd), date('s', $cd), 4, date('d', $cd), date('Y', $cd))));
        }
    }

}
