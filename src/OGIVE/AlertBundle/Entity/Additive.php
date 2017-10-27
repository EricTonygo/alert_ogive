<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Additive
 *
 * @ORM\Table(name="additive")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\AdditiveRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Additive extends AlertProcedure
{
    
   /**
     * @var \CallOffer
     *
     * @ORM\ManyToOne(targetEntity="CallOffer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="callOffer", referencedColumnName="id")
     * })
     */
    private $callOffer;
    
    /**
     * @var \ExpressionInterest
     *
     * @ORM\ManyToOne(targetEntity="ExpressionInterest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="expressionInterest", referencedColumnName="id")
     * })
     */
    private $expressionInterest;
    
    /**
     * Contructor
     */
    public function __construct() {
        parent::__construct();
        $this->setType("Additif");
    }
    
    /**
     * Set callOffer
     *
     * @param \OGIVE\AlertBundle\Entity\CallOffer $callOffer
     *
     * @return ProcedureResult
     */
    public function setCallOffer(\OGIVE\AlertBundle\Entity\CallOffer $callOffer=null) {
        $this->callOffer = $callOffer;

        return $this;
    }

    /**
     * Get callOffer
     *
     * @return \OGIVE\AlertBundle\Entity\CallOffer
     */
    public function getCallOffer() {
        return $this->callOffer;
    }
    
    /**
     * Set expressionInterest
     *
     * @param \OGIVE\AlertBundle\Entity\ExpressionInterest $expressionInterest
     *
     * @return ProcedureResult
     */
    public function setExpressionInterest(\OGIVE\AlertBundle\Entity\ExpressionInterest $expressionInterest=null) {
        $this->expressionInterest = $expressionInterest;

        return $this;
    }

    /**
     * Get expressionInterest
     *
     * @return \OGIVE\AlertBundle\Entity\ExpressionInterest
     */
    public function getExpressionInterest() {
        return $this->expressionInterest;
    }
    
    /**
     * @ORM\PrePersist() 
     */
    public function prePersist() {
        $this->createDate = new \DateTime();
        $this->lastUpdateDate = new \DateTime();
        $this->deadline = new \DateTime();
        $this->openingDate = new \DateTime();
        $this->sendingDate = new \DateTime('now');
        $this->status = 1;
    }
    
    public function getAbstractForSmsNotification() {
        $abstract = "";
        if ($this->getCallOffer()) {
            $abstract = "Réf : " . $this->getType() . " " . "N°" . $this->getReference() . "/" . date("Y", strtotime($this->getPublicationDate())) . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " relatif à " . $this->getCallOffer()->getType() . " N°" . $this->getCallOffer()->getReference() . "/" . $this->getCallOffer()->getType() . "/" . $this->getCallOffer()->getOwner() . "/" . date("Y", strtotime($this->getCallOffer()->getPublicationDate())) . " du " . date("d/m/Y", strtotime($this->getCallOffer()->getPublicationDate())) . ".";
        } elseif ($this->getExpressionInterest()) {
            $abstract = "Réf : " . $this->getType() . " " . "N°" . $this->getReference() . "/" . date("Y", strtotime($this->getPublicationDate())) . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " relatif à " . $this->getExpressionInterest()->getType() . " N°" . $this->getExpressionInterest()->getReference() . "/" . $this->getExpressionInterest()->getType() . "/" . $this->getExpressionInterest()->getOwner() . "/" . date("Y", strtotime($this->getExpressionInterest()->getPublicationDate())) . " du " . date("d/m/Y", strtotime($this->getExpressionInterest()->getPublicationDate())) . '.';
        } else {
            if (strlen(trim($this->getObject())) > 160) {
                $abstract = trim(substr($this->getObject(), 0, 157)) . "...";
            } else {
                $abstract = trim($this->getObject());
            }
        }
        return $abstract;
    }
}

