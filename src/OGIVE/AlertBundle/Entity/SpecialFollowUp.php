<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialFollowUp
 *
 * @ORM\Table(name="special_follow_up")
 * @ORM\Entity(repositoryClass="OGIVE\AlertBundle\Repository\SpecialFollowUpRepository")
 */
class SpecialFollowUp
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Subscriber", inversedBy="specialFollowUps", cascade={"persist"})
     * 
     */
    private $subscribers;
    
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
        $this->status = 1;
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
     * @return SpecialFollowUp
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
     * Set description
     *
     * @param string $description
     *
     * @return SpecialFollowUp
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set status
     *
     * @param integer $status
     *
     * @return SpecialFollowUp
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
     * Add subscriber
     *
     * @param OGIVE\AlertBundle\Entity\Subscriber $subscriber
     * @return SpecialFollowUp
     */
    public function addSubscriber(OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $this->subscribers[] = $subscriber;
        return $this;
    }

    /**
     * Get subscribers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscribers() {
        return $this->subscribers;
    }

    /**
     * Set subscribers
     *
     * @param \Doctrine\Common\Collections\Collection $subscribers
     * @return SpecialFollowUp
     */
    public function setSubscribers(\Doctrine\Common\Collections\Collection $subscribers = null) {
        $this->subscribers = $subscribers;

        return $this;
    }

    /**
     * Remove subscribers
     *
     * @param OGIVE\AlertBundle\Entity\Subscriber $subscriber
     * @return SpecialFollowUp
     */
    public function removeSubscriber(OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $this->entreprises->removeElement($subscriber);
        return $this;
    }
    
    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return SpecialFollowUp
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
     * @return SpecialFollowUp
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
}

