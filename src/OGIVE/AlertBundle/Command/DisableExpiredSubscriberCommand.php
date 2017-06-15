<?php

namespace OGIVE\AlertBundle\Command;

//use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use OGIVE\AlertBundle\Entity\Subscriber;
use OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription;

/**
 * Description of DisableExpiredSubscriber
 *
 * @author Eric TONYE
 */
class DisableExpiredSubscriberCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                // the name of the command (the part after "app/console")
                ->setName('app:disable-expired-subscriber')

                // the short description shown while running "php app/console list"
                ->setDescription('Disables an expired subscriber.')

                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('This command allows you to disable all subscribers which a suscription is  out to date...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $repositorySubscriber = $this->getContainer()->get('doctrine')->getEntityManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $repositoryHistoricalSubscriberSubscription = $this->getContainer()->get('doctrine')->getEntityManager()->getRepository('OGIVEAlertBundle:HistoricalSubscriberSubscription');
        $historicalSubscriberSubscription = new HistoricalSubscriberSubscription();
        $subscriber = new Subscriber();
        $subscribers = $repositorySubscriber->findBy(array('status' => 1, 'state' => 1));
        $admin_message="";
        foreach ($subscribers as $subscriber) {
            $historics = $repositoryHistoricalSubscriberSubscription->findBy(array('subscriber' => $subscriber, 'status' => 1), array('createDate' => 'DESC'), 1, 0);
            $now = new \DateTime('now');
            if ($historics && !empty($historics)) {
                $historicalSubscriberSubscription = $historics[0];
                $expirationDate = $historicalSubscriberSubscription->getExpirationDate();
                $expirationTime = strtotime($expirationDate->format('Y-m-d H:i:s'));
                $interval = date_create('today')->diff( new \DateTime(date('Y-m-d', $expirationTime)));
                setlocale(LC_TIME, 'fr_FR');
                if($subscriber->getState() == 1 && $now < $expirationDate && $interval->d ==7 && $interval->m == 0 && $interval->y == 0){
                    $message = 'Mmes/Mrs les dirrigeants de ' . $subscriber->getEntreprise()->getName() . ', votre abonnement au service "APPELS D\'OFFRES INFOS" expirera le ' . date('d-m-Y', $expirationTime) . ' à ' . date('H', $expirationTime) . 'h' . date('i', $expirationTime) . '. Prière de passer dans nos services renouveller votre abonnement ou contacter :  243 80 38 95/694 20 03 10';
                    $this->sendExpirationSubscriptionMessage($subscriber, $message);
                    $this->getContainer()->get('mail_service')->sendMail($subscriber->getEmail(), 'Rappel de l\'expiration de votre abonnement au service "APPELS D\'OFFRES INFOS"', $message);
                    $admin_message .= 'Le compte de l\'abonné '.$subscriber->getPhoneNumber().' '.$subscriber->getEntreprise()->getName(). 'expirera le '. date('d-m-Y', $expirationTime) . ' à ' . date('H', $expirationTime) . 'h' . date('i', $expirationTime);
                    //$output->writeln($subscriber->getPhoneNumber() . ' expirera dans '.$interval->d." Jours");
                    $this->getContainer()->get('mail_service')->sendMail('genastlev01@gmail.com', 'Rappel de l\'expiration de votre abonnement au service "APPELS D\'OFFRES INFOS"', $admin_message);
                }
                if ($now > $expirationDate && $subscriber->getState() == 1) {
                    $message = 'Mmes/Mrs les dirrigeants de ' . $subscriber->getEntreprise()->getName() . ', votre abonnement au service "APPELS D\'OFFRES INFOS" a expiré depuis le ' . date('d-m-Y', $expirationTime) . 'à ' . date('H', $expirationTime) . 'h' . date('i', $expirationTime) . '. Prière de passer dans nos services renouveller votre abonnement ou contacter :  243 80 38 95/694 20 03 10';
                    $subscriber->setState(0);
                    $subscriber->setExpiredState(1);
                    $repositorySubscriber->updateSubscriber($subscriber);
                    $curl_response = $this->get('curl_service')->disableSubscriberAccount($subscriber, 1);
                    $this->sendExpirationSubscriptionMessage($subscriber, $message);
                    $this->getContainer()->get('mail_service')->sendMail($subscriber->getEmail(), 'Expiration de votre abonnement au service "APPELS D\'OFFRES INFOS"', $message);
                    $admin_message .= 'Abonné '.$subscriber->getPhoneNumber().' '.$subscriber->getEntreprise()->getName(). 'a été désactivé : Abonnement expiré';
                    //$output->writeln($subscriber->getPhoneNumber() . ' a été désactivé');
                    $this->getContainer()->get('mail_service')->sendMail('genastlev01@gmail.com', 'Expiration d\'un abonnement au service "APPELS D\'OFFRES INFOS"', $admin_message);
                } elseif ($now < $expirationDate && $subscriber->getState() == 0) {
                    $message = 'Mmes/Mrs les dirrigeants de ' . $subscriber->getEntreprise()->getName() . ', votre abonnement au service "APPELS D\'OFFRES INFOS" a été réactivé avec succès. OGIVE SOLUTIONS vous remercie pour votre confiance.' ;
                    $subscriber->setState(1);
                    $subscriber->setExpiredState(0);
                    $repositorySubscriber->updateSubscriber($subscriber);
                    $curl_response = $this->get('curl_service')->enableSubscriberAccount($subscriber, 0);
                    $this->sendExpirationSubscriptionMessage($subscriber, $message);
                    $this->getContainer()->get('mail_service')->sendMail($subscriber->getEmail(), 'Réactivation de votre abonnement au service "APPELS D\'OFFRES INFOS"', $message);
                    $admin_message .='Abonné '.$subscriber->getPhoneNumber().' '.$subscriber->getEntreprise()->getName(). ' a été réactivé';  
                    //$output->writeln($subscriber->getPhoneNumber() . ' a été réactivé');
                    $this->getContainer()->get('mail_service')->sendMail('genastlev01@gmail.com', 'Réactivation d\'un abonnement au service "APPELS D\'OFFRES INFOS"', $admin_message);
                }
            }
        }
        //$this->getContainer()->get('mail_service')->sendMail('infos@siogive.com', 'Tache cron du '.$now->format('d-m-Y H:s:i'), $admin_message."Taches Cron Terminées avec succès");
        $output->writeln('Taches Cron Terminées avec succès');
    }

    private function sendExpirationSubscriptionMessage(Subscriber $subscriber, $body) {
        if ($subscriber) {
            $this->getContainer()->get('sms_service')->sendSms($subscriber->getPhoneNumber(), $body);
        }
    }

    private function ago($datetime) {
        $interval = date_create('now')->diff($datetime);
        //$suffix = ( $interval->invert ? ' ago' : '' );
        $suffix = "";
        if ($v = $interval->y >= 1)
            return pluralize($interval->y, 'an(s)') . $suffix;
        if ($v = $interval->m >= 1)
            return pluralize($interval->m, 'mois') . $suffix;
        if ($v = $interval->d >= 1)
            return pluralize($interval->d, 'jour(s)') . $suffix;
        if ($v = $interval->h >= 1)
            return pluralize($interval->h, 'heure(s)') . $suffix;
        if ($v = $interval->i >= 1)
            return pluralize($interval->i, 'minute(s)') . $suffix;
        return pluralize($interval->s, 'seconde(s)') . $suffix;
    }
}
