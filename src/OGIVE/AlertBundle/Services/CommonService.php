<?php

namespace OGIVE\AlertBundle\Services;

/**
 * Description of MailService
 *
 * @author Eric TONYE
 */
class CommonService {

    protected $mail_service;
    protected $sms_service;
    
    public function __construct(\OGIVE\AlertBundle\Services\MailService $mail_service, \OGIVE\AlertBundle\Services\SmsService $sms_service) {
        $this->mail_service = $mail_service;
        $this->sms_service = $sms_service;
    }
    
    public function sendSubscriptionConfirmation(\OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $historiqueAlertSubscriber = new \OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $cout = "";
        if ($subscriber->getSubscription()->getPeriodicity() === 1) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 1 an";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 2) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 6 mois";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 3) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 3 mois";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 4) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 1 mois";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 4) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 1 semaine";
        }
        $content = $subscriber->getEntreprise()->getName() . ", votre souscription au service <<Appels d'offres Infos>> a été éffectuée avec succès. \nCoût du forfait = " . $cout . ". \nOGIVE SOLUTIONS vous remercie pour votre confiance.";
        //$this->sms_service->sendSms($subscriber->getPhoneNumber(), $content);
        $this->mail_service->sendMail($subscriber->getEmail(), "CONFIRMATION DE L'ABONNEMENT", $content);
        $historiqueAlertSubscriber->setMessage($content);
        $historiqueAlertSubscriber->setSubscriber($subscriber);
        $historiqueAlertSubscriber->setAlertType("SMS_CONFIRMATION_SUBSCRIPTION");
        return $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
    }
    
    //get string date as dd/mm/yy
    public function getStringDateForSms($date){
        return date("d", strtotime($date))."/".date("m", strtotime($date))."/".substr(date("Y", strtotime($date), -2));
    }

}
