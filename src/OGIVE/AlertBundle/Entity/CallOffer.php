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
     *   @ORM\JoinColumn(name="domain", referencedColumnName="id")
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
}

