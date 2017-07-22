<?php

namespace OGIVE\AlertBundle\Services;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class CurlService {

    public function __construct() {
        
    }

    private function getProcedurePostData(\OGIVE\AlertBundle\Entity\AlertProcedure $procedure){
        $postData = array(
            'reference' => $procedure->getReference(), // TODO: Specify the recipient's number here. NOT the gateway number
            'subject' => $procedure->getAbstract(),
            'main_domain' => $procedure->getDomain() ? $procedure->getDomain()->getName() : "",
            'sub_domain' => $procedure->getSubDomain() ? $procedure->getSubDomain()->getName() : "",
        );
        $piecesjointes = $procedure->getPiecesjointes();
        $originalpiecesjointes = $procedure->getOriginalpiecesjointes();
        if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
            for ($i = 0; $i < count($piecesjointes); $i++) {
                $filename = $procedure->getUploadRootDir() . '/' . $piecesjointes[$i];
                $pathinfo = pathinfo($filename);
                if (file_exists($filename)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $postData['detail_files['.$i.']'] = curl_file_create($filename, finfo_file($finfo, $filename), $originalpiecesjointes[$i].".".$pathinfo['extension']);
                }
            }
        }        
        return $postData;
    }
    public function createSubscriberAccount(\OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $postData = array(
            'username' => $subscriber->getPhoneNumber(), // TODO: Specify the recipient's number here. NOT the gateway number
            'email' => $subscriber->getEmail(),
            'password' => $subscriber->getPhoneNumber(),
            'last_name' => $subscriber->getEntreprise()->getName(),
            'first_name' => "",
            'state' => 1,
            'expired_state' => $subscriber->getExpiredState()
        );
        $url = $this->get_home_url_website().'/inscription';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function updateSubscriberAccount(\OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $postData = array(
            'username' => $subscriber->getPhoneNumber(), // TODO: Specify the recipient's number here. NOT the gateway number
            'email' => $subscriber->getEmail(),
            'password' => $subscriber->getPhoneNumber(),
            'last_name' => $subscriber->getEntreprise()->getName(),
            'first_name' => "",
            'state' => 1,
            'expired_state' => $subscriber->getExpiredState(),
            'action' => 'update'
        );
        $url = $this->get_home_url_website().'/inscription';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function enableSubscriberAccount(\OGIVE\AlertBundle\Entity\Subscriber $subscriber, $expiredState = null) {
        $postData = array(
            'username' => $subscriber->getPhoneNumber(), // TODO: Specify the recipient's number here. NOT the gateway number
            'email' => $subscriber->getEmail(),
            'password' => $subscriber->getPhoneNumber(),
            'last_name' => $subscriber->getEntreprise()->getName(),
            'first_name' => "",
            'state' => 1,
            'expired_state' => $expiredState,
            'action' => 'enable'
        );
        $url = $this->get_home_url_website().'/inscription';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function disableSubscriberAccount(\OGIVE\AlertBundle\Entity\Subscriber $subscriber, $expiredState = null) {
        $postData = array(
            'username' => $subscriber->getPhoneNumber(), // TODO: Specify the recipient's number here. NOT the gateway number
            'email' => $subscriber->getEmail(),
            'password' => $subscriber->getPhoneNumber(),
            'last_name' => $subscriber->getEntreprise()->getName(),
            'first_name' => "",
            'state' => 0,
            'expired_state' => $expiredState,
            'action' => 'disable'
        );
        $url = $this->get_home_url_website().'/inscription';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function sendAdditiveToWebsite(\OGIVE\AlertBundle\Entity\Additive $additive) {
        $postData = $this->getProcedurePostData($additive);
        $url = $this->get_home_url_website().'/additifs';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    public function sendCallOfferToWebsite(\OGIVE\AlertBundle\Entity\CallOffer $callOffer) {
        $postData = $this->getProcedurePostData($callOffer);
        $url = $this->get_home_url_website().'/appels-doffres';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    public function sendProcedureResultToWebsite(\OGIVE\AlertBundle\Entity\ProcedureResult $procedureResult) {
        $postData = $this->getProcedurePostData($procedureResult);
        $url = $this->get_home_url_website().'/attributions';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    
    public function sendExpressionInterestToWebsite(\OGIVE\AlertBundle\Entity\ExpressionInterest $expressionInterest) {
       $postData = $this->getProcedurePostData($expressionInterest);
        $url = $this->get_home_url_website().'/manifestations-dinteret';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    private function get_home_url_website(){
        return 'http://www.siogive.com';
    }

}
