<?php

namespace OGIVE\AlertBundle\Services;

use Twilio\Rest\Client;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class SmsService {

    protected $twilio_client;
    

    public function __construct() {
        $this->twilio_client = new Client($this->username, $this->password);
    }

    public function sendSms($phoneNumber, $message) {
        if ($phoneNumber != "") {
            if ($phoneNumber == "+237671034458" || $phoneNumber == "+237670034454" || $phoneNumber == "+237671034453" || $phoneNumber == "+237671034445") {
                if (substr(trim($message), -1) === ".") {
                    $message += " OGIVE SOLUTIONS. Tel: 243803895/694200310";
                }else{
                    $message += " . OGIVE SOLUTIONS. Tel: 243803895/694200310";
                }                
            }
            $this->twilio_client->messages->create(
                    $phoneNumber, // Text any number
                    array(
                'from' => 'OGIVE INFOS', // From a Twilio number in your account
                'body' => $message
                    )
            );
        } else {
            return true;
        }
    }

}
