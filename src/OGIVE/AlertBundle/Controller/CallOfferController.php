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
     * @Rest\Get("/calls-offer" , name="call_offer_index", options={ "method_prefix" = false })
     * @param Request $request
     */
    public function getCallOffersAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $callOffer = new CallOffer();
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer);
        $callOffers = $em->getRepository('OGIVEAlertBundle:CallOffer')->getAll();
        return $this->render('OGIVEAlertBundle:callOffer:index.html.twig', array(
                    'callOffers' => $callOffers,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/call-ffer/{id}" , name="call_offer_get_one", options={ "method_prefix" = false })
     */
    public function getCallOfferByIdAction(CallOffer $callOffer) {
        if (empty($callOffer)) {
            return new JsonResponse(['message' => "Appel d'offre introuvable"], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer, array('method' => 'PUT'));
        $callOffer_details = $this->renderView('OGIVEAlertBundle:callOffer:show.html.twig', array(
            'callOffer' => $callOffer,
            'form' => $form->createView()
        ));
        $view = View::create(["code" => 200, 'callOffer' => $callOffer, 'callOffer_details' => $callOffer_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/calls-offer")
     */
    public function postCallOffersAction(Request $request) {
        $callOffer = new CallOffer();
        $repositoryCallOffer = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:CallOffer');
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositoryCallOffer->findOneBy(array('reference' => $callOffer->getReference(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => "Un appel d'offre avec ce nom existe dejà !"], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $callOffer->setState(1);
            }
            $abstract = $callOffer->getType()." : "."N°".$callOffer->getReference()." du ".date_format($callOffer->getPublicationDate(), "d/m/Y")." pour ".$callOffer->getObject().". Dépôt des offres du ".date_format($callOffer->getOpeningDate(), "d/m/Y")." à ".date_format($callOffer->getOpeningDate(), "H:i")." au ".date_format($callOffer->getDeadline(), "d/m/Y")." à ".date_format($callOffer->getOpeningDate(), "H:i"); 
            $callOffer->setAbstract($abstract);
            $callOffer = $repositoryCallOffer->saveCallOffer($callOffer);
            $callOffer_content_grid = $this->renderView('OGIVEAlertBundle:callOffer:callOffer-grid.html.twig', array('callOffer' => $callOffer));
            $callOffer_content_list = $this->renderView('OGIVEAlertBundle:callOffer:callOffer-list.html.twig', array('callOffer' => $callOffer));
            /* @var $callOffer CallOffer */
            $view = View::create(["code" => 200, 'callOffer' => $callOffer, 'callOffer_content_grid' => $callOffer_content_grid, 'callOffer_content_list' => $callOffer_content_list]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'callOffer' => $callOffer, 'callOffer_content_grid' => $callOffer_content_grid, 'callOffer_content_list' => $callOffer_content_list], Response::HTTP_OK);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/calls-offer/{id}")
     */
    public function removeCallOfferAction(CallOffer $callOffer) {

        $repositoryCallOffer = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:CallOffer');
        if ($callOffer) {
            $repositoryCallOffer->deleteCallOffer($callOffer);
            $view = View::create(['callOffer' => $callOffer, "message" => "Appel d'offre supprimé avec succès !"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => "Appel d'offre introuvable"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/calls-offer/{id}", name="call_offer_update", options={ "method_prefix" = false })
     * @param Request $request
     */
    public function putCallOfferAction(Request $request, CallOffer $callOffer) {

        return $this->updateCallOfferAction($request, $callOffer);
    }

    public function updateCallOfferAction(Request $request, CallOffer $callOffer) {

        $repositoryCallOffer = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:CallOffer');

        if (empty($callOffer)) {
            return new JsonResponse(['message' => "Appel d'offre introuvable"], Response::HTTP_NOT_FOUND);
        }
        
        if($request->get('action')== 'enable'){
            $callOffer->setState(1);
            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            return new JsonResponse(['message' => "Appel d'offre activé avec succcès !"], Response::HTTP_OK
                    );
        }
        
        if($request->get('action')== 'disable'){
            $callOffer->setState(0);
            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            return new JsonResponse(['message' => "Appel d'offre désactivé avec succcès !"], Response::HTTP_OK
                    );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $callOfferUnique = $repositoryCallOffer->findOneBy(array('reference' => $callOffer->getReference(), 'status' => 1));
            if ($callOfferUnique && $callOfferUnique->getId() != $callOffer->getId()) {
                return new JsonResponse(["success" => false, 'message' => "Un appel d'offre avec ce nom existe dejà"], Response::HTTP_NOT_FOUND);
            }
            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            $callOffer_content_grid = $this->renderView('OGIVEAlertBundle:callOffer:callOffer-grid-edit.html.twig', array('callOffer' => $callOffer));
            $callOffer_content_list = $this->renderView('OGIVEAlertBundle:callOffer:callOffer-list-edit.html.twig', array('callOffer' => $callOffer));
            /* @var $callOffer CallOffer */
            $view = View::create(["code" => 200, 'callOffer' => $callOffer, 'callOffer_content_grid' => $callOffer_content_grid, 'callOffer_content_list' => $callOffer_content_list]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_callOffer_form = $this->renderView('OGIVEAlertBundle:callOffer:edit.html.twig', array('form' => $form->createView(), 'callOffer' => $callOffer));
            /* @var $callOffer CallOffer */
            $view = View::create(["code" => 200, 'callOffer' => $callOffer, 'edit_callOffer_form' => $edit_callOffer_form]);
            $view->setFormat('json');
            return $view;
        }
    }

}
