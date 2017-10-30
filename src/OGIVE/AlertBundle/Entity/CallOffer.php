<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Call_offer
 *
 * @ORM\Table(name="call_offer")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\CallOfferRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CallOffer extends AlertProcedure {

    /**
     * @var \ExpressionInterest
     *
     * @ORM\ManyToOne(targetEntity="ExpressionInterest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="expressionInterest", referencedColumnName="id", nullable=true)
     * })
     */
    protected $expressionInterest;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        //$this->setType("AAO");
    }

    /**
     * Set expressionInterest
     *
     * @param \OGIVE\AlertBundle\Entity\ExpressionInterest $expressionInterest
     *
     * @return CallOffer
     */
    public function setExpressionInterest(\OGIVE\AlertBundle\Entity\ExpressionInterest $expressionInterest = null) {
        $this->expressionInterest = $expressionInterest;

        return $this;
    }

    /**
     * Get status
     *
     * @return \OGIVE\AlertBundle\Entity\ExpressionInterest
     */
    public function getExpressionInterest() {
        return $this->expressionInterest;
    }

    public function getAbstractForSmsNotification() {
        if (strlen(trim($this->getObject())) > 58) {
            $object_abstract = trim(substr($this->getObject(), 0, 55));
            $dot = "...";
            if (substr($object_abstract, -1) === ".") {
                $dot = "..";
            }
        } else {
            $object_abstract = trim($this->getObject());
            $dot = ".";
            if (substr(trim($object_abstract), -1) === ".") {
                $dot = "";
            }
        }
        $subject_array = explode(" ", $this->getObject());
        $subject = "";
        if(strlen($subject_array[0])>3){
            $subject = $subject_array[0];
        }else{
            $subject = $subject_array[0]." ".$subject_array[1];
        }
        //$abstract = $this->getReference() . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " lancé par " . $this->getOwner() . " pour " . $object_abstract . $dot . " Dépôt des offres le " . date("d/m/Y", strtotime($this->getOpeningDate())) . " à " . date("H:i", strtotime($this->getOpeningDate())) . '.';
        $abstract = $this->getType() . " : " . "N°". explode("/", $this->getReference())[0] . " du " . $this->getStringDateForSms($this->getPublicationDate()) . " lancé par " . $this->getOwner(). " pour " .$subject.". Dépôt des offres le " . $this->getStringDateForSms($this->getOpeningDate()) . " à " . date("H:i", strtotime($this->getOpeningDate())) . ". Détail à " . $this->getUrlDetails();
        return $abstract;
    }

    //get string date as dd/mm/yy
    public function getStringDateForSms($date){
        return date("d", strtotime($date))."/".date("m", strtotime($date))."/".substr(date("Y", strtotime($date)), -2);
    }
}
