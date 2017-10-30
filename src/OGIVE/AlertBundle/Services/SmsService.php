<?php

namespace OGIVE\AlertBundle\Services;

use Twilio\Rest\Client;

require 'Osms.php';

use Osms;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class SmsService {

    protected $twilio_client;
    private $username = "ACdda206efadd5de15c5f0121a6a982a9c";
    private $password = "e77b1a26567145aa789a3dc0d701dea0";

    public function __construct() {
        $this->twilio_client = new Client($this->username, $this->password);
    }

    public function sendTwilioSms($phoneNumber, $message) {
        if ($phoneNumber != "") {
            if ($phoneNumber == "+237671034458" || $phoneNumber == "+237670034454" || $phoneNumber == "+237671034453" || $phoneNumber == "+237671034445") {
                if (substr(trim($message), -1) === ".") {
                    $message .= " OGIVE SOLUTIONS. Tel: 243803895/694200310";
                } else {
                    $message .= " . OGIVE SOLUTIONS. Tel: 243803895/694200310";
                }
            }
            try {
                $this->twilio_client->messages->create(
                        $phoneNumber, // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $message
                        )
                );
                return true;
            } catch (Exception $ex) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function sendSms($phoneNumber, $message) {
        if ($phoneNumber != "") {
            $config = array(
                //'token' => "MskEAyO9WuuKsljXho8VuGP263VE",
                'token' => "BuBQvwnAZZPHw4EVjbPv5h2D9cYU"
            );
            try {
                $osms = new Osms\Osms($config);
                $osms->setVerifyPeerSSL(false);
                $response = $osms->sendSms(
                        // sender
                        'tel:+237699001539',
                        //'tel:+237699213790',
                        // receiver
                        'tel:' . $phoneNumber,
                        // message
                        $message
                );
                if (empty($response['error'])) {
                    return true;
                } else {
                    //$this->sendTwilioSms($phoneNumber, $message);
                    return true;
                }
            } catch (Exception $ex) {
                //$this->sendTwilioSms($phoneNumber, $message);
                return true;
            }
        } else {
            return false;
        }
    }

}
