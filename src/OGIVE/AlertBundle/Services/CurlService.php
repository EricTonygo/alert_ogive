<?php

namespace OGIVE\AlertBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class CurlService {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    private function getProcedurePostData(\OGIVE\AlertBundle\Entity\AlertProcedure $procedure) {
        $postData = array(
            'reference' => $procedure->getReference(), // TODO: Specify the recipient's number here. NOT the gateway number
            'subject' => $procedure->getAbstract(),
            'main_domain' => $procedure->getDomain() ? $procedure->getDomain()->getName() : "",
            'sub_domain' => $procedure->getSubDomain() ? $procedure->getSubDomain()->getName() : "",
            'owner' => $procedure->getOwner(),
            'publication_date' => $procedure->getPublicationDate(),
            'opening_date' => $procedure->getOpeningDate(),
            'deadline' => $procedure->getDeadline()
        );
        $piecesjointes = $procedure->getPiecesjointes();
        $originalpiecesjointes = $procedure->getOriginalpiecesjointes();
        if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
            for ($i = 0; $i < count($piecesjointes); $i++) {
                $filename = $procedure->getUploadRootDir() . '/' . $piecesjointes[$i];
                $pathinfo = pathinfo($filename);
                if (file_exists($filename)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $postData['detail_files[' . $i . ']'] = curl_file_create($filename, finfo_file($finfo, $filename), $originalpiecesjointes[$i] . "." . $pathinfo['extension']);
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
        $url = $this->get_home_url_website() . '/inscription';
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
        $url = $this->get_home_url_website() . '/inscription';
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
        $url = $this->get_home_url_website() . '/inscription';
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
        $url = $this->get_home_url_website() . '/inscription';
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
        $url = $this->get_home_url_website() . '/additifs';
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
        $url = $this->get_home_url_website() . '/appels-doffres';
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
        $url = $this->get_home_url_website() . '/attributions';
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
        $url = $this->get_home_url_website() . '/manifestations-dinteret';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function sendSmsning($message, $recipients) {
//        $postData = array(
//            "sender" => $this->container->getParameter("sender_smsngin"),
//            "recipients" => $recipients,
//            "message" => $message,
//            "username" => $this->container->getParameter("username_smsngin"),
//            "password" => $this->container->getParameter("password_smsngin")
//        );
//        $url = $this->container->getParameter("url_smsngin");
//        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type", "application/x-www-form-urlencoded"));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        return $response;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "8587",
            CURLOPT_URL => "https://www.smsngin.com:8587/api/sms/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "sender=Tenders-Inf&username=Tenders19&password=%23tenders19&recipients=".$recipients."&message=".$message,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    private function get_home_url_website() {
        return 'http://localhost/siogive.com';
    }

}
