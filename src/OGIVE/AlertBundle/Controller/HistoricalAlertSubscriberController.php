<?php

namespace OGIVE\AlertBundle\Controller;

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
use Twilio\Rest\Client;

/**
 * HistoricalAlertSubscriber controller.
 *
 */
class HistoricalAlertSubscriberController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/historical-sms-subscribers" , name="historicalAlertSubscriber_sms_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getHistoricalAlertSubscribersAction(Request $request) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $subscriber = new Subscriber();
        $myMessages = array();
        $twilio = $this->get('twilio.client');
        $messages = $twilio->messages->read();
        $maxResults = 8;
        $page = 1;
        if ($request->get('page')) {
            $page = intval($request->get('page'));
        }
        $start_from = ($page - 1) * $maxResults;
        $end_from = $start_from + $maxResults;
        $total_pages = ceil(count($messages) / $maxResults);
        for ($i = $start_from; $i < $end_from; $i++) {
            $subscriber = $em->getRepository('OGIVEAlertBundle:Subscriber')->findOneBy(array('phoneNumber' => $messages[$i]->to, 'status' => 1));
                $myMessages[] = array("subscriber" => $subscriber, "to" => $messages[$i]->to, 'body' => $messages[$i]->body, 'dateSent' => $messages[$i]->dateSent, 'price' => $messages[$i]->price, "status" => $messages[$i]->status);           
        }
        return $this->render('OGIVEAlertBundle:historicalAlertSubscriber:index_sms.html.twig', array(
                    'messages' => $myMessages,
                    'total_pages' => $total_pages,
                    'page' => $page
        ));
    }

}
