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
     * Contructor
     */
    public function __construct() {
        parent::__construct();
        $this->setType("Additif");
    }
}

