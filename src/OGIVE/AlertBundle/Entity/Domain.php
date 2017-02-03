<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Domain
 *
 * @ORM\Table(name="domain")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\DomainRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Domain
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
     * @ORM\Column(name="description", type="text", nullable=true)
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
     * @ORM\ManyToMany(targetEntity="\OGIVE\AlertBundle\Entity\Entreprise", inversedBy="domains", cascade={"persist"})
     * @ORM\JoinTable(name="domain_entreprise",
     * joinColumns={@ORM\JoinColumn(name="domain_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")}
     * )
     */
    private $entreprises;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\OGIVE\AlertBundle\Entity\SubDomain", mappedBy="domain", cascade={"remove", "persist"})
     */
    private $subDomains;

    /**
     * Constructor
     */
    public function __construct() {
        $this->state = 0;
        $this->entreprises = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subDomains = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Domain
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
     * @return Domain
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
     * @return Domain
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
     * @return Domain
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
     * @return Domain
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
     * @return Domain
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
     * @return Domain
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
     * @return Domain
     */
    public function setEntreprises(\Doctrine\Common\Collections\Collection $entreprises = null) {
        $this->entreprises = $entreprises;

        return $this;
    }

    /**
     * Remove entreprise
     *
     * @param \OGIVE\AlertBundle\Entity\Entreprise $entreprise
     * @return Domain
     */
    public function removeEntreprise(\OGIVE\AlertBundle\Entity\Entreprise $entreprise) {
        $this->entreprises->removeElement($entreprise);
        return $this;
    }
    
    /**
     * Add subDomain
     *
     * @param \OGIVE\AlertBundle\Entity\SubDomain $subDomain 
     * @return Domain
     */
    public function addSubDomain(\OGIVE\AlertBundle\Entity\SubDomain $subDomain) {
        if (!$this->subDomains->contains($subDomain)) {
            $subDomain->setDomain($this);
            $this->subDomains[] = $subDomain;
        }
        return $this;
    }

    /**
     * Get subDomains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubDomains() {
        return $this->subDomains;
    }

    /**
     * Set subDomains
     *
     * @param \Doctrine\Common\Collections\Collection $subDomains
     * @return Domain
     */
    public function setSubDomains(\Doctrine\Common\Collections\Collection $subDomains = null) {
        foreach ($subDomains as $subDomain) {
            $this->addSubDomain($subDomain);
        }
        return $this;
    }

    /**
     * Remove subDomain
     *
     * @param \OGIVE\AlertBundle\Entity\SubDomain $subDomain
     * @return Domain
     */
    public function removeSubDomain(\OGIVE\AlertBundle\Entity\SubDomain $subDomain) {
        $this->subDomains->removeElement($subDomain);
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
    
}

