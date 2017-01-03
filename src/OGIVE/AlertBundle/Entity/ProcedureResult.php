<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProcedureResult
 *
 * @ORM\Table(name="procedure_result")
 * @ORM\Entity(repositoryClass="OGIVE\AlertBundle\Repository\ProcedureResultRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProcedureResult extends AlertProcedure
{
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
}

