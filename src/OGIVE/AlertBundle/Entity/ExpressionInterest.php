<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpressionInterest
 *
 * @ORM\Table(name="expression_interest")
 * @ORM\Entity(repositoryClass="\OGIVE\AlertBundle\Repository\ExpressionInterestRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ExpressionInterest extends AlertProcedure {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->setType("ASMI");
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
        //$abstract = $this->getReference() . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " lance par " . $this->getOwner() . " pour " . $object_abstract . $dot . " Depot des offres le " . date("d/m/Y", strtotime($this->getOpeningDate())) . " a " . date("H:i", strtotime($this->getOpeningDate())) . ".";
        $abstract = $this->getReference() . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " lance par " . $this->getOwner() . ". Dépot des offres le " . date("d/m/Y", strtotime($this->getOpeningDate())) . " à " . date("H:i", strtotime($this->getOpeningDate())) . ". Plus d'infos sur " . $this->getUrlDetails();
        return $abstract;
    }

}
