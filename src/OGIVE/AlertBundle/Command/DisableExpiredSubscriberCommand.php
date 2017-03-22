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
        foreach ($subscribers as $subscriber) {
            $historics = $repositoryHistoricalSubscriberSubscription->findBy(array('subscriber' => $subscriber, 'status' => 1), array('subscriptionDate' => 'desc'), 1, 0);
            $today = new \DateTime('now');
            if ($historics && !empty($historics)) {
                $historicalSubscriberSubscription = $historics[0];
                $expirationDate = $historicalSubscriberSubscription->getExpirationDate();
                if ($today > $expirationDate) {
                    $subscriber->setState(0);
                    $repositorySubscriber->updateSubscriber($subscriber);
                    $this->sendExpirationSubscriptionMessage($subscriber, $historicalSubscriberSubscription);
                    $output->writeln($subscriber->getPhoneNumber().'a été désactivé');
                }
            }
        }
        
    }

    private function sendExpirationSubscriptionMessage(Subscriber $subscriber, HistoricalSubscriberSubscription $historicalSubscriberSubscription) {
        $twilio = $this->getContainer()->get('twilio.client');
        setlocale(LC_TIME, 'fr_FR');
        $expirationDate = $historicalSubscriberSubscription->getExpirationDate();
        $expirationTime = strtotime($expirationDate->format('Y-m-d H:i:s'));
        $body = 'Mmes/Mrs les dirrigeants de ' . $subscriber->getEntreprise()->getName() . ', votre abonnement à "Appels d\'Offres Infos" a expiré depuis le ' . date('d-m-Y', $expirationTime) . 'à ' . date('H', $expirationTime) . 'h' . date('i', $expirationTime) . '. Prière de passer dans nos services renouveller votre abonnement ou contacter :  243 80 38 95/694 20 03 10';
        $message = $twilio->messages->create(
                $subscriber->getPhoneNumber(), // Text any number
                array(
            'from' => 'OGIVE INFOS', // From a Twilio number in your account
            'body' => $body
                )
        );
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
