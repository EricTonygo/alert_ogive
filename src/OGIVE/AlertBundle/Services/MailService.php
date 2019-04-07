<?php

namespace OGIVE\AlertBundle\Services;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class MailService {

    protected $mailer;
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, \Symfony\Bundle\TwigBundle\TwigEngine $templating) {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendMailForWebsiteAccount($email, $subject, $content, $attachements = null) {
        if ($email != "") {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array('contact@tenders-infos.com' => "TENDERS INFOS"))
                    ->setTo($email)
                    ->setBody(
                    $content
            );
            $this->mailer->send($message);
        } else {
            return true;
        }
    }

    public function sendMail($email, $subject, $content, $attachements = null) {
        if ($email != "") {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array('contact@tenders-infos.com' => "TENDERS INFOS"))
                    ->setTo($email)
                    ->setBody(
                    $this->templating->render('OGIVEAlertBundle:send_mail:template_send_procedure.html.twig', array('content' => $content)), 'text/html'
            );
            $this->mailer->send($message);
        } else {
            return true;
        }
    }

    public function sendEmailSubscriber(\OGIVE\AlertBundle\Entity\Subscriber $subscriber, $subject, $content, \OGIVE\AlertBundle\Entity\AlertProcedure $procedure = null) {
        if ($subscriber && $subscriber->getEmail() != "") {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array('contact@tenders-infos.com' => "TENDERS INFOS"))
                    ->setTo($subscriber->getEmail())
                    ->setBody(
                    $this->templating->render('OGIVEAlertBundle:send_mail:template_send_procedure.html.twig', array('content' => $content)), 'text/html'
            );
            if ($procedure && ($procedure->getUrlDetails() == null || $procedure->getUrlDetails() =="")) {
                $piecesjointes = $procedure->getPiecesjointes();
                $originalpiecesjointes = $procedure->getOriginalpiecesjointes();
                if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
                    for ($i = 0; $i < count($piecesjointes); $i++) {
                        if (file_exists($procedure->getUploadRootDir() . '/' . $piecesjointes[$i])) {
                            $attachment = \Swift_Attachment::fromPath($procedure->getUploadRootDir() . '/' . $piecesjointes[$i])
                                    ->setFilename($originalpiecesjointes[$i]);
                            $message->attach($attachment);
                        }
                    }
                }
            }

            $this->mailer->send($message);
        } else {
            return true;
        }
    }

}
