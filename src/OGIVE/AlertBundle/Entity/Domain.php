<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 *
 * @ORM\Table(name="domain")
 * @ORM\Entity(repositoryClass="OGIVE\AlertBundle\Repository\DomainRepository")
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
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OGIVE\AlertBundle\Entity\Entreprise", mappedBy="domain", cascade={"remove", "persist"})
     */
    private $entreprises;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OGIVE\AlertBundle\Entity\AlertProcedure", mappedBy="domain", cascade={"remove", "persist"})
     */
    private $alertProcedures;

    /**
     * Constructor
     */
    public function __construct() {
        $this->status = 1;
        $this->createDate = new \Datetime();
        $this->lastUpdateDate = new \Datetime();
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
     * @param string $status
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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    
    /**
     * Add entreprise
     *
     * @param OGIVE\AlertBundle\Entity\Entreprise $entreprise 
     * @return Domain
     */
    public function addEntreprise(OGIVE\AlertBundle\Entity\Entreprise $entreprise) {
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
     * @param OGIVE\AlertBundle\Entity\Entreprise $entreprise
     * @return Domain
     */
    public function removeEntreprise(OGIVE\AlertBundle\Entity\Entreprise $entreprise) {
        $this->entreprises->removeElement($entreprise);
        return $this;
    }
    
    /**
     * Add alertProcedure
     *
     * @param OGIVE\AlertBundle\Entity\AlertProcedure $alertProcedure 
     * @return Domain
     */
    public function addAlertProcedure(OGIVE\AlertBundle\Entity\AlertProcedure $alertProcedure) {
        $this->alertProcedures[] = $alertProcedure;
        return $this;
    }

    /**
     * Get alertProcedures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlertProcedures() {
        return $this->alertProcedures;
    }

    /**
     * Set alertProcedures
     *
     * @param \Doctrine\Common\Collections\Collection $alertProcedures
     * @return Domain
     */
    public function setAlertProcedures(\Doctrine\Common\Collections\Collection $alertProcedures = null) {
        $this->alertProcedures = $alertProcedures;

        return $this;
    }

    /**
     * Remove entreprise
     *
     * @param OGIVE\AlertBundle\Entity\AlertProcedure $alertProcedure
     * @return Domain
     */
    public function removeAlertProcedure(OGIVE\AlertBundle\Entity\AlertProcedure $alertProcedure) {
        $this->alertProcedures->removeElement($alertProcedure);
        return $this;
    }
}

