<?php

namespace OGIVE\AlertBundle\Services;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class MailService {
    public function sendMail($email, $subject, $content, $attachements=null) {
        if($email!=""){
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array('infos@si-ogive.com' => "OGIVE INFOS"))
                ->setTo($email)
                ->setBody(
                $content
        );
        $this->get('mailer')->send($message);
        }else{
            return true;
        }
    }
}
