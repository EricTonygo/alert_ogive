<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProcedureResult
 *
 * @ORM\Table(name="procedure_result")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\ProcedureResultRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProcedureResult extends AlertProcedure
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
     * Constructor
     */
    public function __construct() {
        parent::__construct();
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
        if (strlen(trim($this->getObject())) > 160) {
            $object_abstract = trim(substr($this->getObject(), 0, 157)) . "...";
        } else {
            $object_abstract = trim($this->getObject());
        }
        if ($this->getCallOffer()) {
            $abstract = $this->getReference() . " portant sur " . $object_abstract . " de l'" . $this->getCallOffer()->getType() . " N°" . $this->getCallOffer()->getReference() ." du " . $this->getStringDateForSms($this->getCallOffer()->getPublicationDate()). ".";
        } elseif ($this->getExpressionInterest()) {
            $abstract = $this->getReference() ." portant sur " . $object_abstract . " de l'" . $this->getExpressionInterest()->getType() . " N°" . $this->getExpressionInterest()->getReference() ." du " . $this->getStringDateForSms($this->getExpressionInterest()->getPublicationDate()) . ".";
        } else {
            $abstract = $object_abstract;
        }
        return $abstract;
    }
    
    //get string date as dd/mm/yy
    public function getStringDateForSms($date){
        return date("d", strtotime($date))."/".date("m", strtotime($date))."/".substr(date("Y", strtotime($date)), -2);
    }
}

