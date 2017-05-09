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

    public function createSubscriberAccount(\OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $postData = array(
            'username' => $subscriber->getPhoneNumber(), // TODO: Specify the recipient's number here. NOT the gateway number
            'email' => $subscriber->getEmail(),
            'password' => $subscriber->getPhoneNumber(),
            'last_name' => $subscriber->getEntreprise()->getName(),
            'first_name' => "",
            'state' => $subscriber->getState(),
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
        $postData = array(
            'reference' => $additive->getReference(), // TODO: Specify the recipient's number here. NOT the gateway number
            'subject' => $additive->getAbstract(),
            'main_domain' => $additive->getDomain() ? $additive->getDomain()->getName() : "",
            'sub_domain' => $additive->getSubDomain() ? $additive->getSubDomain()->getName() : "",
        );
        $piecesjointes = $additive->getPiecesjointes();
        $originalpiecesjointes = $additive->getOriginalpiecesjointes();
        if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
            for ($i = 0; $i < count($piecesjointes); $i++) {
                $filename = $additive->getUploadRootDir() . '/' . $piecesjointes[$i];
                $pathinfo = pathinfo($filename);
                if (file_exists($filename)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $postData['additive_files['.$i.']'] = curl_file_create($filename, finfo_file($finfo, $filename), $originalpiecesjointes[$i].".".$pathinfo['extension']);
                }
            }
        }
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
        $postData = array(
            'reference' => $callOffer->getReference(), // TODO: Specify the recipient's number here. NOT the gateway number
            'subject' => $callOffer->getAbstract(),
            'main_domain' => $callOffer->getDomain() ? $callOffer->getDomain()->getName() : "",
            'sub_domain' => $callOffer->getSubDomain() ? $callOffer->getSubDomain()->getName() : "",
        );
        $piecesjointes = $callOffer->getPiecesjointes();
        $originalpiecesjointes = $callOffer->getOriginalpiecesjointes();
        if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
            for ($i = 0; $i < count($piecesjointes); $i++) {
                $filename = $callOffer->getUploadRootDir() . '/' . $piecesjointes[$i];
                $pathinfo = pathinfo($filename);
                if (file_exists($filename)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $postData['call_offer_files['.$i.']'] = curl_file_create($filename, finfo_file($finfo, $filename), $originalpiecesjointes[$i].".".$pathinfo['extension']);
                }
            }
        }
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
        $postData = array(
            'reference' => $procedureResult->getReference(), // TODO: Specify the recipient's number here. NOT the gateway number
            'subject' => $procedureResult->getAbstract(),
            'main_domain' => $procedureResult->getDomain() ? $procedureResult->getDomain()->getName() : "",
            'sub_domain' => $procedureResult->getSubDomain() ? $procedureResult->getSubDomain()->getName() : "",
        );
        $piecesjointes = $procedureResult->getPiecesjointes();
        $originalpiecesjointes = $procedureResult->getOriginalpiecesjointes();
        if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
            for ($i = 0; $i < count($piecesjointes); $i++) {
                $filename = $procedureResult->getUploadRootDir() . '/' . $piecesjointes[$i];
                $pathinfo = pathinfo($filename);
                if (file_exists($filename)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $postData['assignment_files['.$i.']'] = curl_file_create($filename, finfo_file($finfo, $filename), $originalpiecesjointes[$i].".".$pathinfo['extension']);
                }
            }
        }
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
        $postData = array(
            'reference' => $expressionInterest->getReference(), // TODO: Specify the recipient's number here. NOT the gateway number
            'subject' => $expressionInterest->getAbstract(),
            'main_domain' => $expressionInterest->getDomain() ? $expressionInterest->getDomain()->getName() : "",
            'sub_domain' => $expressionInterest->getSubDomain() ? $expressionInterest->getSubDomain()->getName() : "",
        );
        $piecesjointes = $expressionInterest->getPiecesjointes();
        $originalpiecesjointes = $expressionInterest->getOriginalpiecesjointes();
        if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
            for ($i = 0; $i < count($piecesjointes); $i++) {
                $filename = $expressionInterest->getUploadRootDir() . '/' . $piecesjointes[$i];
                $pathinfo = pathinfo($filename);
                if (file_exists($filename)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $postData['expression_interest_files['.$i.']'] = curl_file_create($filename, finfo_file($finfo, $filename), $originalpiecesjointes[$i].".".$pathinfo['extension']);
                }
            }
        }
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
        return 'http://localhost/siogive.com';
    }

}
