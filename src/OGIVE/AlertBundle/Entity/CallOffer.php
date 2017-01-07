<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Call_offer
 *
 * @ORM\Table(name="call_offer")
 * @ORM\Entity(repositoryClass="OGIVE\AlertBundle\Repository\CallOfferRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CallOffer extends AlertProcedure
{
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\OGIVE\AlertBundle\Entity\ProcedureResult", mappedBy="callOffer", cascade={"remove", "persist"})
     */
    private $procedureResults;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->setType("AAO");
    }
    
    /**
     * Add procedureResult
     *
     * @param \OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult 
     * @return CallOffer
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
     * @return CallOffer
     */
    public function setProcedureResults(\Doctrine\Common\Collections\Collection $procedureResults = null) {
        $this->procedureResults = $procedureResults;

        return $this;
    }

    /**
     * Remove procedureResult
     *
     * @param \OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult
     * @return CallOffer
     */
    public function removeProcedureResult(\OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult) {
        $this->procedureResults->removeElement($procedureResult);
        return $this;
    }
}

