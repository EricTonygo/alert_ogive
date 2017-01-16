<?php

use OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber;
use OGIVE\AlertBundle\Entity\Subscriber;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;

class TelephoneController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Post("/send-sms-subscriber/{id}" , name="send_sms_subscriber", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendSmsSubscriberAction(Request $request, Subscriber $subscriber) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $form = $this->createForm('OGIVE\AlertBundle\Form\HistoricalAlertSubscriberType', $historiqueAlertSubscriber);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $twilio = $this->get('twilio.api');
            $message = $twilio->account->messages->sendMessage(
                    '+237697704889', // From a Twilio number in your account
                    '+237698918085', // Text any number
                    $historiqueAlertSubscriber->getMessage()
            );
            $historiqueAlertSubscriber->setSubscriber($subscriber);
            $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            $view = View::create(["code" => 200, 'message_sid' => $message->sid, 'message' => "SMS envoyé avec succès" ]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $send_sms_subscriber_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_sms.html.twig', array(
                'subscriber' => $subscriber,
                'form' => $form->createView()
            ));
            $view = View::create(["code" => 200, 'subscriber' => $subscriber, 'send_sms_subscriber_form' => $send_sms_subscriber_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    public function callAction($me, $maybee) {
        //returns an instance of Vresh\TwilioBundle\Service\TwilioWrapper
        $twilio = $this->get('twilio.api');

        $message = $twilio->account->messages->sendMessage(
                '+237697704889', // From a Twilio number in your account
                '+237698918085', // Text any number
                "UHKOMBEUL Test !"
        );

        //get an instance of \Service_Twilio
        $otherInstance = $twilio->createInstance('BBBB', 'CCCCC');

        return new Response($message->sid);
    }

}
