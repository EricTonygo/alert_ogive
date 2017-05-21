<?php

namespace OGIVE\AlertBundle\Controller;

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

/**
 * ExpressionInterest controller.
 *
 */
class ExpressionInterestController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/expressions-interest" , name="expressionInterest_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getExpressionInterestsAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $expressionInterest = new ExpressionInterest();
        $page = 1;
        $maxResults = 4;
        $route_param_page = array();
        $route_param_search_query = array();
        $search_query = null;
        $placeholder = "Rechercher un ASMI...";
        if ($request->get('page')) {
            $page = intval(htmlspecialchars(trim($request->get('page'))));
            $route_param_page['page'] = $page;
        }
        if ($request->get('search_query')) {
            $search_query = htmlspecialchars(trim($request->get('search_query')));
            $route_param_search_query['search_query'] = $search_query;
        }
        $start_from = ($page - 1) * $maxResults >= 0 ? ($page - 1) * $maxResults : 0;
        $total_pages = ceil(count($em->getRepository('OGIVEAlertBundle:ExpressionInterest')->getAllByString($search_query)) / $maxResults);
        $form = $this->createForm('OGIVE\AlertBundle\Form\ExpressionInterestType', $expressionInterest);
        $expressionInterests = $em->getRepository('OGIVEAlertBundle:ExpressionInterest')->getAll($start_from, $maxResults, $search_query);
        return $this->render('OGIVEAlertBundle:expressionInterest:index.html.twig', array(
                    'expressionInterests' => $expressionInterests,
                    'total_pages' => $total_pages,
                    'page' => $page,
                    'form' => $form->createView(),
                    'route_param_page' => $route_param_page,
                    'route_param_search_query' => $route_param_search_query,
                    'search_query' => $search_query,
                    'placeholder' => $placeholder
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/expressions-interest/{id}" , name="expressionInterest_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getExpressionInterestByIdAction(ExpressionInterest $expressionInterest) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($expressionInterest)) {
            return new JsonResponse(['message' => "Manifestation d'intérêt introuvable"], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\ExpressionInterestType', $expressionInterest, array('method' => 'PUT'));
        $expressionInterest_details = $this->renderView('OGIVEAlertBundle:expressionInterest:show.html.twig', array(
            'expressionInterest' => $expressionInterest,
            'form' => $form->createView()
        ));
        $view = View::create(['expressionInterest_details' => $expressionInterest_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/expressions-interest", name="expressionInterest_add", options={ "method_prefix" = false, "expose" = true })
     */
    public function postExpressionInterestsAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $expressionInterest = new ExpressionInterest();
        $repositoryExpressionInterest = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:ExpressionInterest');

        if ($request->get('testunicity') == 'yes' && $request->get('reference')) {
            $reference = $request->get('reference');
            if ($repositoryExpressionInterest->findOneBy(array('reference' => $reference, 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => "Une manifestation d'intérêt avec cette référence existe dejà !"], Response::HTTP_BAD_REQUEST);
            } else {
                return new JsonResponse(['message' => "Add Expression of interest is possible !"], Response::HTTP_OK);
            }
        }

        $form = $this->createForm('OGIVE\AlertBundle\Form\ExpressionInterestType', $expressionInterest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $expressionInterest->setState(1);
                }
            }
            $expressionInterest->setAbstract($this->getAbstractOfExpressionInterest($expressionInterest));
            $expressionInterest = $repositoryExpressionInterest->saveExpressionInterest($expressionInterest);
            $curl_response = $this->get('curl_service')->sendExpressionInterestToWebsite($expressionInterest);
            $curl_response_array = json_decode($curl_response, true);
            if ($curl_response_array['data']['url'] && $curl_response_array['data']['url'] != "") {
                $expressionInterest->setAbstract($this->getAbstractOfExpressionInterest($expressionInterest, $curl_response_array['data']['url']));
                $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            }
            $view = View::createRedirect($this->generateUrl('expressionInterest_index'));
            $view->setFormat('html');
            return $view;
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/expressions-interest/{id}", name="expressionInterest_delete", options={ "method_prefix" = false, "expose" = true })
     */
    public function removeExpressionInterestAction(ExpressionInterest $expressionInterest) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositoryExpressionInterest = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:ExpressionInterest');
        if ($expressionInterest) {
            $repositoryExpressionInterest->deleteExpressionInterest($expressionInterest);
            $view = View::create(["message" => "Manifestation d'intérêt supprimée avec succès !"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => "Manifestation d'intérêt introuvable"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/expressions-interest/{id}", name="expressionInterest_update", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function putExpressionInterestAction(Request $request, ExpressionInterest $expressionInterest) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->updateExpressionInterestAction($request, $expressionInterest);
    }

    public function updateExpressionInterestAction(Request $request, ExpressionInterest $expressionInterest) {

        $repositoryExpressionInterest = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:ExpressionInterest');

        if (empty($expressionInterest)) {
            return new JsonResponse(['message' => "Manifestation d'intérêt introuvable"], Response::HTTP_NOT_FOUND);
        }

        if ($request->get('testunicity') == 'yes' && $request->get('reference')) {
            $reference = $request->get('reference');
            $expressionInterestUnique = $repositoryExpressionInterest->findOneBy(array('reference' => $reference, 'status' => 1));
            if ($expressionInterestUnique && $expressionInterestUnique->getId() != $expressionInterest->getId()) {
                return new JsonResponse(["success" => false, 'message' => "Une manifestation d'intérêt avec cette référence existe dejà"], Response::HTTP_BAD_REQUEST);
            } else {
                return new JsonResponse(['message' => "Update Expression of interest is possible !"], Response::HTTP_OK);
            }
        }

        if ($request->get('action') == 'enable') {
            $expressionInterest->setState(1);
            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            return new JsonResponse(['message' => "Manifestation d'intérêt activée avec succcès !"], Response::HTTP_OK
            );
        }

        if ($request->get('action') == 'disable') {
            $expressionInterest->setState(0);
            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            return new JsonResponse(['message' => "Manifestation d'intérêt désactivée avec succcès !"], Response::HTTP_OK
            );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\ExpressionInterestType', $expressionInterest, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $expressionInterest->setState(1);
                } else {
                    $expressionInterest->setState(0);
                }
            }
            $expressionInterest->setAbstract($this->getAbstractOfExpressionInterest($expressionInterest));
            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            $curl_response = $this->get('curl_service')->sendExpressionInterestToWebsite($expressionInterest);
            $curl_response_array = json_decode($curl_response, true);
            if ($curl_response_array['data']['url'] && $curl_response_array['data']['url'] != "") {
                $expressionInterest->setAbstract($this->getAbstractOfExpressionInterest($expressionInterest, $curl_response_array['data']['url']));
                $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            }
            $view = View::createRedirect($this->generateUrl('expressionInterest_index'));
            $view->setFormat('html');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        } else {
            $edit_expressionInterest_form = $this->renderView('OGIVEAlertBundle:expressionInterest:edit.html.twig', array('form' => $form->createView(), 'expressionInterest' => $expressionInterest));
            $view = View::create(['edit_expressionInterest_form' => $edit_expressionInterest_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    public function getAbstractOfExpressionInterest(ExpressionInterest $expressionInterest, $detail_url = null) {
        $dot = ".";
        if ($expressionInterest) {
            if (substr(trim($expressionInterest->getObject()), -1) === ".") {
                $dot = "";
            }
            $abstract = $expressionInterest->getType() . " : " . "N°" . $expressionInterest->getReference() . " du " . date("d/m/Y", strtotime($expressionInterest->getPublicationDate())) . " lancé par " . $expressionInterest->getOwner() . " pour " . $expressionInterest->getObject() . $dot . " Dépôt des offres le " . date("d/m/Y", strtotime($expressionInterest->getOpeningDate())) . " à " . date("H:i", strtotime($expressionInterest->getOpeningDate())) . ".";
            if ($detail_url && $detail_url != "") {
                $abstract .= " Détail téléchargeable à l'adresse " . $detail_url;
            }
            return $abstract;
        } else {
            return "";
        }
    }

}
