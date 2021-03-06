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
        $subject_array = explode(" ", $this->getObject());
        $subject = "";
        if(strlen($subject_array[0])>3){
            $subject = $subject_array[0];
        }else{
            $subject = $subject_array[0]." ".$subject_array[1]." ".$subject_array[2]." ".$subject_array[3];
        }
        if(strtolower($subject) == "l'exécution" || strtolower($subject) == "l'execution" || strtolower($subject) == "l'éxécution"){
            $subject = 'les travaux';
        }
        //$abstract = $this->getReference() . " du " . date("d/m/Y", strtotime($this->getPublicationDate())) . " lancé par " . $this->getOwner() . " pour " . $object_abstract . $dot . " Dépôt des offres le " . date("d/m/Y", strtotime($this->getOpeningDate())) . " à " . date("H:i", strtotime($this->getOpeningDate())) . ".";
        $abstract = $this->getType() . " : " . "N°". explode("/", $this->getReference())[0] . " du " . $this->getStringDateForSms($this->getPublicationDate()) . " lance par " . $this->getOwner(). " pour " .$subject. ". Depot des offres le " .$this->getStringDateForSms($this->getOpeningDate()). " a " . date("H:i", strtotime($this->getOpeningDate()));
        if($this->getUrlDetails() && $this->getUrlDetails() != ""){
            $abstract .= ". Detail a " . $this->getUrlDetails();
        }else{
            $abstract .= ". Detail dans votre boite de messagerie.";
        }
        return $abstract;
    }
    
    //get string date as dd/mm/yy
    public function getStringDateForSms($date){
        return date("d", strtotime($date))."/".date("m", strtotime($date))."/".substr(date("Y", strtotime($date)), -2);
    }

}
