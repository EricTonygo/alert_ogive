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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\OGIVE\AlertBundle\Entity\ProcedureResult", mappedBy="expressionInterest", cascade={"remove", "persist"})
     */
    private $procedureResults;
    
    /**
     * Constructor
     */
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->setType("ASMI");
    }
    
    /**
     * Add procedureResult
     *
     * @param \OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult 
     * @return ExpressionInterest
     */
    public function addProcedureResult(\OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult) {
        $this->procedureResults[] = $procedureResult;
        return $this;
    }

    /**
     * Get procedureResults
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProcedureResults() {
        return $this->procedureResults;
    }
    
    /**
     * Set procedureResults
     *
     * @param \Doctrine\Common\Collections\Collection $procedureResults
     * @return ExpressionInterest
     */
    public function setProcedureResults(\Doctrine\Common\Collections\Collection $procedureResults = null) {
        $this->procedureResults = $procedureResults;

        return $this;
    }

    /**
     * Remove procedureResult
     *
     * @param \OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult
     * @return ExpressionInterest
     */
    public function removeProcedureResult(\OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult) {
        $this->procedureResults->removeElement($procedureResult);
        return $this;
    }
}

