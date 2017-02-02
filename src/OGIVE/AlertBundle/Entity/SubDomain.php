<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SubDomain
 *
 * @ORM\Table(name="sub_domain")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\SubDomainRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SubDomain
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
     * 
     * @Assert\NotBlank(
     *     message = "Le nom ne peut pas être vide."
     * )
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
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
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    private $state;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\ManyToMany(targetEntity="\OGIVE\AlertBundle\Entity\Entreprise", inversedBy="subDomains", cascade={"persist"})
     * @ORM\JoinTable(name="sub_domain_entreprise",
     * joinColumns={@ORM\JoinColumn(name="sub_domain_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")}
     * )
     */
    private $entreprises;
    
    /**
     * @var \Domain
     *
     * @ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="domain", referencedColumnName="id")
     * })
     */
    private $domain;

    /**
     * Constructor
     */
    public function __construct() {
        $this->state = 0;
        $this->entreprises = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SubDomain
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
     * Set name
     *
     * @param string $description
     *
     * @return SubDomain
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
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return SubDomain
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
     * @return SubDomain
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
     * Set status
     *
     * @param integer $status
     *
     * @return SubDomain
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
     * @return SubDomain
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
     * Add entreprise
     *
     * @param \OGIVE\AlertBundle\Entity\Entreprise $entreprise 
     * @return SubDomain
     */
    public function addEntreprise(\OGIVE\AlertBundle\Entity\Entreprise $entreprise) {
        $this->entreprises[] = $entreprise;
        return $this;
    }

    /**
     * Get entreprises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntreprises() {
        return $this->entreprises;
    }

    /**
     * Set entreprises
     *
     * @param \Doctrine\Common\Collections\Collection $entreprises
     * @return SubDomain
     */
    public function setEntreprises(\Doctrine\Common\Collections\Collection $entreprises = null) {
        $this->entreprises = $entreprises;

        return $this;
    }

    /**
     * Remove entreprise
     *
     * @param \OGIVE\AlertBundle\Entity\Entreprise $entreprise
     * @return SubDomain
     */
    public function removeEntreprise(\OGIVE\AlertBundle\Entity\Entreprise $entreprise) {
        $this->entreprises->removeElement($entreprise);
        return $this;
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
    
    
    /**
     * Set domain
     *
     * @param \OGIVE\AlertBundle\Entity\Domain $domain
     *
     * @return SubDomain
     */
    public function setDomain(\OGIVE\AlertBundle\Entity\Domain $domain) {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \OGIVE\AlertBundle\Entity\Domain
     */
    public function getDomain() {
        return $this->domain;
    }
}

