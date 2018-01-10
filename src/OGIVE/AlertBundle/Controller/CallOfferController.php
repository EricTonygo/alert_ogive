<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\CallOffer;
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
 * CallOffer controller.
 *
 */
class CallOfferController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/calls-offer" , name="call_offer_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getCallOffersAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $callOffer = new CallOffer();
        $page = 1;
        $maxResults = 6;
        $route_param_page = array();
        $route_param_search_query = array();
        $search_query = null;
        $start_date = null;
        $end_date = null;
        $owner = null;
        $domain = null;
        $placeholder = "Rechercher un appel d'offre...";
        if ($request->get('page')) {
            $page = intval(htmlspecialchars(trim($request->get('page'))));
            $route_param_page['page'] = $page;
        }
        if ($request->get('search_query')) {
            $search_query = htmlspecialchars(trim($request->get('search_query')));
            $route_param_search_query['search_query'] = $search_query;
        }
        if ($request->get('start-date')) {
            $start_date = htmlspecialchars(trim($request->get('start-date')));
            $route_param_search_query['start-date'] = $start_date;
        }
        if ($request->get('end-date')) {
            $end_date = htmlspecialchars(trim($request->get('end-date')));
            $route_param_search_query['end-date'] = $end_date;
        }
        if ($request->get('owner')) {
            $owner = htmlspecialchars(trim($request->get('owner')));
            $route_param_search_query['owner'] = $owner;
        }
        if ($request->get('domain')) {
            $domain = htmlspecialchars(trim($request->get('domain')));
            $route_param_search_query['domain'] = $domain;
        }
        $start_from = ($page - 1) * $maxResults >= 0 ? ($page - 1) * $maxResults : 0;
        $total_procedures = count($em->getRepository('OGIVEAlertBundle:CallOffer')->getAllByQueriedParameters($search_query, $start_date, $end_date, $owner, $domain));
        $total_pages = ceil($total_procedures / $maxResults);
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer);
        $callOffers = $em->getRepository('OGIVEAlertBundle:CallOffer')->getAll($start_from, $maxResults, $search_query, $start_date, $end_date, $owner, $domain);
        $owners = $em->getRepository('OGIVEAlertBundle:Owner')->findBy(array("state"=>1, "status"=>1));
        $domains = $em->getRepository('OGIVEAlertBundle:Domain')->findBy(array("state"=>1, "status"=>1));
        if($start_date && $end_date){
            //$this->get('common_service')->getStatisticsOfProceduresByOwner($start_date, $end_date);
            $this->get('common_service')->getStatisticsOfProceduresByMonth($start_date, $end_date);
        }
        return $this->render('OGIVEAlertBundle:callOffer:index.html.twig', array(
                    'callOffers' => $callOffers,
                    'total_procedures' => $total_procedures,
                    'total_pages' => $total_pages,
                    'page' => $page,
                    'form' => $form->createView(),
                    'route_param_page' => $route_param_page,
                    'route_param_search_query' => $route_param_search_query,
                    'search_query' => $search_query,
                    'placeholder' => $placeholder,
                    'owners' => $owners,
                    'domains'=> $domains,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'queried_owner'=> $owner,
                    'queried_domain'=> $domain,
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/calls-offer/{id}" , name="call_offer_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getCallOfferByIdAction(CallOffer $callOffer) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($callOffer)) {
            return new JsonResponse(['message' => "Appel d'offre introuvable"], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer, array('method' => 'PUT'));
        $callOffer_details = $this->renderView('OGIVEAlertBundle:callOffer:show.html.twig', array(
            'callOffer' => $callOffer,
            'form' => $form->createView()
        ));
        $view = View::create(['callOffer_details' => $callOffer_details]);
        $view->setFormat('json');
        return $view;
//        return new JsonResponse(["code" => 200, 'callOffer_details' => $callOffer_details], Response::HTTP_OK);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/calls-offer", name="call_offer_add", options={ "method_prefix" = false, "expose" = true })
     */
    public function postCallOffersAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $callOffer = new CallOffer();
        $repositoryCallOffer = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:CallOffer');
        $repositoryOwner = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Owner');

        if ($request->get('testunicity') == 'yes' && $request->get('reference')) {
            $reference = $request->get('reference');
            if ($repositoryCallOffer->findOneBy(array('reference' => $reference, 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => "Un appel d'offre avec cette référence existe dejà !"], Response::HTTP_BAD_REQUEST);
            } else {
                return new JsonResponse(['message' => "Add Call offer is possible !"], Response::HTTP_OK);
            }
        }

        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $callOffer->setState(1);
                }
            }
            $callOffer->setType($request->get('call_offer_type'));
            $callOffer->setAbstract($this->getAbstractOfCallOffer($callOffer));
            $user = $this->getUser();
            $callOffer->setUser($user);
            $callOffer = $repositoryCallOffer->saveCallOffer($callOffer);
            $curl_response = $this->get('curl_service')->sendCallOfferToWebsite($callOffer);
            $curl_response_array = json_decode($curl_response, true);
            $callOffer->setUrlDetails($curl_response_array['data']['url']);
            $callOffer->setAbstract($this->getAbstractOfCallOffer($callOffer, $callOffer->getUrlDetails()));
            $repositoryCallOffer->updateCallOffer($callOffer);
            $repositoryOwner->saveOwnerForProcedure($callOffer);
            $view = View::createRedirect($this->generateUrl('call_offer_index'));
            $view->setFormat('html');
            return $view;
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
//            return new JsonResponse($form, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/calls-offer/{id}", name="call_offer_delete", options={ "method_prefix" = false, "expose" = true })
     */
    public function removeCallOfferAction(CallOffer $callOffer) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositoryCallOffer = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:CallOffer');
        if ($callOffer) {
            $repositoryCallOffer->deleteCallOffer($callOffer);
            $view = View::create(["message" => "Appel d'offre supprimé avec succès !"]);
            $view->setFormat('json');
            return $view;
//            return new JsonResponse(["message" => "Appel d'offre supprimé avec succès !"], Response::HTTP_OK);
        } else {
            return new JsonResponse(["message" => "Appel d'offre introuvable"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/calls-offer/{id}", name="call_offer_update", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function putCallOfferAction(Request $request, CallOffer $callOffer) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->updateCallOfferAction($request, $callOffer);
    }

    public function updateCallOfferAction(Request $request, CallOffer $callOffer) {

        $repositoryCallOffer = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:CallOffer');
        $repositoryOwner = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Owner');

        if (empty($callOffer)) {
            return new JsonResponse(['message' => "Appel d'offre introuvable"], Response::HTTP_NOT_FOUND);
        }

        if ($request->get('testunicity') == 'yes' && $request->get('reference')) {
            $reference = $request->get('reference');
            $callOfferUnique = $repositoryCallOffer->findOneBy(array('reference' => $reference, 'status' => 1));
            if ($callOfferUnique && $callOfferUnique->getId() != $callOffer->getId()) {
                return new JsonResponse(["success" => false, 'message' => "Un appel d'offre avec cette référence existe dejà"], Response::HTTP_BAD_REQUEST);
            } else {
                return new JsonResponse(['message' => "Update Call offer is possible !"], Response::HTTP_OK);
            }
        }

        if ($request->get('action') == 'enable') {
            $callOffer->setState(1);
//            $curl_response = $this->get('curl_service')->sendCallOfferToWebsite($callOffer);
//            $curl_response_array = json_decode($curl_response, true);
//            $callOffer->setAbstract($this->getAbstractOfCallOffer($callOffer,  $curl_response_array['data']['url']));
            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            return new JsonResponse(['message' => "Appel d'offre activé avec succcès !"], Response::HTTP_OK);
        }

        if ($request->get('action') == 'disable') {
            $callOffer->setState(0);
//            $curl_response = $this->get('curl_service')->sendCallOfferToWebsite($callOffer);
//            $curl_response_array = json_decode($curl_response, true);
//            $callOffer->setAbstract($this->getAbstractOfCallOffer($callOffer,  $curl_response_array['data']['url']));
            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            return new JsonResponse(['message' => "Appel d'offre désactivé avec succcès !"], Response::HTTP_OK
            );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $callOffer->setType($request->get('call_offer_type'));

            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $callOffer->setState(1);
                } else {
                    $callOffer->setState(0);
                }
            }
            $callOffer->setAbstract($this->getAbstractOfCallOffer($callOffer));
            $user = $this->getUser();
            $callOffer->setUpdatedUser($user);
            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            $curl_response = $this->get('curl_service')->sendCallOfferToWebsite($callOffer);
            $curl_response_array = json_decode($curl_response, true);
            $callOffer->setUrlDetails($curl_response_array['data']['url']);
            $callOffer->setAbstract($this->getAbstractOfCallOffer($callOffer, $callOffer->getUrlDetails()));
            $repositoryCallOffer->updateCallOffer($callOffer);
            $repositoryOwner->saveOwnerForProcedure($callOffer);
            $view = View::createRedirect($this->generateUrl('call_offer_index'));
            $view->setFormat('html');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse($form, Response::HTTP_BAD_REQUEST);
        } else {
            $edit_callOffer_form = $this->renderView('OGIVEAlertBundle:callOffer:edit.html.twig', array('form' => $form->createView(), 'callOffer' => $callOffer));
            $view = View::create(['edit_callOffer_form' => $edit_callOffer_form]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["code" => 200, 'edit_callOffer_form' => $edit_callOffer_form], Response::HTTP_OK);
        }
    }

    public function getAbstractOfCallOffer(CallOffer $callOffer, $detail_url = null) {
        if ($callOffer) {
            $dot = ".";
            if (substr(trim($callOffer->getObject()), -1) === ".") {
                $dot = "";
            }
            $abstract = $callOffer->getType() . " : " . "N°" . $callOffer->getReference() . " du " . date("d/m/Y", strtotime($callOffer->getPublicationDate())) . " lancé par " . $callOffer->getOwner() . " pour " . $callOffer->getObject() . $dot . " Dépôt des offres le " . date("d/m/Y", strtotime($callOffer->getOpeningDate())) . " à " . date("H:i", strtotime($callOffer->getOpeningDate())) . '.';
            if ($detail_url && $detail_url != "") {
                $abstract.= " Détail téléchargeable à l'adresse ".$detail_url;
            }
            return $abstract;
        } else {
            return "";
        }
    }

}
