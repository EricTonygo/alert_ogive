<?php

namespace OGIVE\AlertBundle\Services;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class MailService {
    protected $mailer;
    
  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

    public function sendMail($email, $subject, $content, $attachements=null) {
        if($email!=""){
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array('infos@siogive.com' => "OGIVE INFOS"))
                ->setTo($email)
                ->setBody(
                $content, 'text/html'
        );
        $this->mailer->send($message);
        }else{
            return true;
        }
    }
    
    public function sendEmailSubscriber(\OGIVE\AlertBundle\Entity\Subscriber $subscriber, $subject, $content, \OGIVE\AlertBundle\Entity\AlertProcedure $procedure = null) {
        if ($subscriber && $subscriber->getEmail() != "") {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array('infos@siogive.com' => "OGIVE INFOS"))
                    ->setTo($subscriber->getEmail())
                    ->setBody(
                    $content, 'text/html'
            );
//            if ($procedure) {
//                $piecesjointes = $procedure->getPiecesjointes();
//                $originalpiecesjointes = $procedure->getOriginalpiecesjointes();
//                if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
//                    for ($i = 0; $i < count($piecesjointes); $i++) {
//                        if (file_exists($procedure->getUploadRootDir() . '/' . $piecesjointes[$i])) {
//                            $attachment = \Swift_Attachment::fromPath($procedure->getUploadRootDir() . '/' . $piecesjointes[$i])
//                                    ->setFilename($originalpiecesjointes[$i]);
//                            $message->attach($attachment);
//                        }
//                    }
//                }
//            }

            $this->mailer->send($message);
        } else {
            return true;
        }
    }
}
