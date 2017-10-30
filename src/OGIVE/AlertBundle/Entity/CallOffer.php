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
        //$abstract = $this->getReference() . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " lance par " . $this->getOwner() . " pour " . $object_abstract . $dot . " Depot des offres le " . date("d/m/Y", strtotime($this->getOpeningDate())) . " a " . date("H:i", strtotime($this->getOpeningDate())) . '.';
        $abstract = $this->getReference() . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " lance par " . $this->getOwner().". Depot des offres le " . date("d/m/Y", strtotime($this->getOpeningDate())) . " a " . date("H:i", strtotime($this->getOpeningDate())) . '. Detail par email ou à ' . $this->getUrlDetails();
        return $abstract;
    }

}
