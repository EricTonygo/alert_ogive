<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpressionInterest
 *
 * @ORM\Table(name="expression_interest")
 * @ORM\Entity(repositoryClass="OGIVE\AlertBundle\Repository\ExpressionInterestRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ExpressionInterest extends AlertProcedure
{
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->setType("ASMI");
    }
}

