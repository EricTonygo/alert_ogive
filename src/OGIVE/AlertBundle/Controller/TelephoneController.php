<?php

use OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber;
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
     * @Rest\Get("/send-sms" , name="send_sms", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendSMSAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $form = $this->createForm('OGIVE\AlertBundle\Form\HistoricalAlertSubscriberType', $historiqueAlertSubscriber);
        return $this->render('OGIVEAlertBundle:send_sms:form_send_sms.html.twig', array(
                    'subscribers' => $subscribers,
                    'form' => $form->createView()
        ));
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
