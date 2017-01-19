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
class CallOffer extends AlertProcedure
{
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
     * @param OGIVE\AlertBundle\Entity\ExpressionInterest $expressionInterest
     *
     * @return CallOffer
     */
    public function setExpressionInterest($expressionInterest) {
        $this->expressionInterest = $expressionInterest;

        return $this;
    }

    /**
     * Get status
     *
     * @return OGIVE\AlertBundle\Entity\ExpressionInterest
     */
    public function getExpressionInterest() {
        return $this->expressionInterest;
    }
}

