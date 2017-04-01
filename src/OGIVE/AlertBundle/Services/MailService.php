<?php

namespace OGIVE\AlertBundle\Services;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class MailService {
    protected $mailer;
    
  // Dans le constructeur, on retire $locale des arguments
  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

    public function sendMail($email, $subject, $content, $attachements=null) {
        if($email!=""){
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array('infos@si-ogive.com' => "OGIVE INFOS"))
                ->setTo($email)
                ->setBody(
                $content
        );
        $this->mailer->send($message);
        }else{
            return true;
        }
    }
}
