<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber;
use OGIVE\AlertBundle\Entity\Subscriber;
use OGIVE\AlertBundle\Entity\CallOffer;
use OGIVE\AlertBundle\Entity\ProcedureResult;
use OGIVE\AlertBundle\Entity\Additive;
use OGIVE\AlertBundle\Entity\ExpressionInterest;
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
            //$messages = $twilio->account->messages->read();
            $message = $twilio->account->messages->sendMessage(
                    'MG8e369c4e5ea49ce989834c5355a1f02f', // From a Twilio number in your account
                    $subscriber->getPhoneNumber(), // Text any number
                    $historiqueAlertSubscriber->getMessage()
            );
            $historiqueAlertSubscriber->setSubscriber($subscriber);
            $historiqueAlertSubscriber->setAlertType("SMS");
            $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            $view = View::create(["code" => 200, 'messages_twilio' => $message, 'message' => "SMS envoyé avec succès"]);
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

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-call-offer/{id}" , name="send_notification_callOffer", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationCallOfferAction(Request $request, CallOffer $callOffer) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $subscribers = null;

        if (isset($_POST['send_notification_callOffer_form'])) {
            $twilio = $this->get('twilio.api');
            //$messages = $twilio->account->messages->read();
            $message = $twilio->account->messages->sendMessage(
                    'MG8e369c4e5ea49ce989834c5355a1f02f', // From a Twilio number in your account
                    $subscriber->getPhoneNumber(), // Text any number
                    $historiqueAlertSubscriber->getMessage()
            );
            $historiqueAlertSubscriber->setSubscriber($subscriber);
            $historiqueAlertSubscriber->setAlertType("SMS");
            $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            $view = View::create(["code" => 200, 'messages_twilio' => $message, 'message' => "SMS envoyé avec succès"]);
            $view->setFormat('json');
            return $view;
//        } elseif ($form->isSubmitted() && !$form->isValid()) {
//            return $form;
        } else {
            $subscribers = $repositorySubscriber->findBy(array("state" => 1, "status" => 1));

            $send_notification_callOffer_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_callOffer.html.twig', array(
                'subscribers' => $subscribers,
                'callOffer' => $callOffer,
            ));
            $view = View::create(["code" => 200, 'send_notification_callOffer_form' => $send_notification_callOffer_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-procedure-result/{id}" , name="send_notification_procedureResult", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationProcedureResultAction(Request $request, ProcedureResult $procedureResult) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $subscribers = null;

        if (isset($_POST['send_notification_procedureResult_form'])) {
            $twilio = $this->get('twilio.api');
            //$messages = $twilio->account->messages->read();
            $message = $twilio->account->messages->sendMessage(
                    'MG8e369c4e5ea49ce989834c5355a1f02f', // From a Twilio number in your account
                    $subscriber->getPhoneNumber(), // Text any number
                    $historiqueAlertSubscriber->getMessage()
            );
            $historiqueAlertSubscriber->setSubscriber($subscriber);
            $historiqueAlertSubscriber->setAlertType("SMS");
            $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            $view = View::create(["code" => 200, 'messages_twilio' => $message, 'message' => "SMS envoyé avec succès"]);
            $view->setFormat('json');
            return $view;
//        } elseif ($form->isSubmitted() && !$form->isValid()) {
//            return $form;
        } else {

            $subscribers = $repositorySubscriber->findBy(array("state" => 1, "status" => 1));

            $send_notification_procedureResult_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_procedureResult.html.twig', array(
                'subscribers' => $subscribers,
                'procedureResult' => $procedureResult,
            ));
            $view = View::create(["code" => 200, 'send_notification_procedureResult_form' => $send_notification_procedureResult_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-additive/{id}" , name="send_notification_additive", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationAdditiveAction(Request $request, Additive $additive) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $subscribers = null;

        if (isset($_POST['send_notification_additive_form'])) {
            $twilio = $this->get('twilio.api');
            //$messages = $twilio->account->messages->read();
            $message = $twilio->account->messages->sendMessage(
                    'MG8e369c4e5ea49ce989834c5355a1f02f', // From a Twilio number in your account
                    $subscriber->getPhoneNumber(), // Text any number
                    $historiqueAlertSubscriber->getMessage()
            );
            $historiqueAlertSubscriber->setSubscriber($subscriber);
            $historiqueAlertSubscriber->setAlertType("SMS");
            $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            $view = View::create(["code" => 200, 'messages_twilio' => $message, 'message' => "SMS envoyé avec succès"]);
            $view->setFormat('json');
            return $view;
//        } elseif ($form->isSubmitted() && !$form->isValid()) {
//            return $form;
        } else {

            $subscribers = $repositorySubscriber->findBy(array("state" => 1, "status" => 1));

            $send_notification_additive_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_additive.html.twig', array(
                'subscribers' => $subscribers,
                'additive' => $additive,
            ));
            $view = View::create(["code" => 200, 'send_notification_additive_form' => $send_notification_additive_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-expression-interest/{id}" , name="send_notification_expressionInterest", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationExpressionInterestAction(Request $request, ExpressionInterest $expressionInterest) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $subscribers = null;

        if (isset($_POST['send_notification_expressionInterest_form'])) {
            $twilio = $this->get('twilio.api');
            //$messages = $twilio->account->messages->read();
            $message = $twilio->account->messages->sendMessage(
                    'MG8e369c4e5ea49ce989834c5355a1f02f', // From a Twilio number in your account
                    $subscriber->getPhoneNumber(), // Text any number
                    $historiqueAlertSubscriber->getMessage()
            );
            $historiqueAlertSubscriber->setSubscriber($subscriber);
            $historiqueAlertSubscriber->setAlertType("SMS");
            $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            $view = View::create(["code" => 200, 'messages_twilio' => $message, 'message' => "SMS envoyé avec succès"]);
            $view->setFormat('json');
            return $view;
//        } elseif ($form->isSubmitted() && !$form->isValid()) {
//            return $form;
        } else {

            $subscribers = $repositorySubscriber->findBy(array("state" => 1, "status" => 1));

            $send_notification_expressionInterest_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_expressionInterest.html.twig', array(
                'subscribers' => $subscribers,
                'expressionInterest' => $expressionInterest,
            ));
            $view = View::create(["code" => 200, 'send_notification_expressionInterest_form' => $send_notification_expressionInterest_form]);
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
