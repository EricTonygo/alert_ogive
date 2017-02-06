<?php

namespace OGIVE\AlertBundle\Controller;

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
        $historicalAlertSubscribers = $em->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber')->getAllByAlertType('SMS');
        return $this->render('OGIVEAlertBundle:historicalAlertSubscriber:index_sms.html.twig', array(
                    'historicalAlertSubscribers' => $historicalAlertSubscribers,
        ));
    }

}
