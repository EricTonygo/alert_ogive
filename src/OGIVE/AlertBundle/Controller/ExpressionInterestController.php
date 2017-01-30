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
        $form = $this->createForm('OGIVE\AlertBundle\Form\ExpressionInterestType', $expressionInterest);
        $expressionInterests = $em->getRepository('OGIVEAlertBundle:ExpressionInterest')->getAll();
        return $this->render('OGIVEAlertBundle:expressionInterest:index.html.twig', array(
                    'expressionInterests' => $expressionInterests,
                    'form' => $form->createView()
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
        $view = View::create(["code" => 200, 'expressionInterest' => $expressionInterest, 'expressionInterest_details' => $expressionInterest_details]);
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
        $form = $this->createForm('OGIVE\AlertBundle\Form\ExpressionInterestType', $expressionInterest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositoryExpressionInterest->findOneBy(array('reference' => $expressionInterest->getReference(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => "Une manifestation d'intérêt avec cette référence existe dejà !"], Response::HTTP_BAD_REQUEST);
            }
           if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $expressionInterest->setState(1);
                }
            }
            $expressionInterest->setAbstract($this->getAbstractOfExpressionInterest($expressionInterest));
            $expressionInterest = $repositoryExpressionInterest->saveExpressionInterest($expressionInterest);
            $expressionInterest_content_grid = $this->renderView('OGIVEAlertBundle:expressionInterest:expressionInterest-grid.html.twig', array('expressionInterest' => $expressionInterest));
            $expressionInterest_content_list = $this->renderView('OGIVEAlertBundle:expressionInterest:expressionInterest-list.html.twig', array('expressionInterest' => $expressionInterest));
            $view = View::create(["code" => 200, 'expressionInterest' => $expressionInterest, 'expressionInterest_content_grid' => $expressionInterest_content_grid, 'expressionInterest_content_list' => $expressionInterest_content_list]);
            $view->setFormat('json');
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
            $view = View::create(['expressionInterest' => $expressionInterest, "message" => "Manifestation d'intérêt supprimé avec succès !"]);
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
        
        if($request->get('action')== 'enable'){
            $expressionInterest->setState(1);
            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            return new JsonResponse(['message' => "Manifestation d'intérêt activée avec succcès !"], Response::HTTP_OK
                    );
        }
        
        if($request->get('action')== 'disable'){
            $expressionInterest->setState(0);
            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            return new JsonResponse(['message' => "Manifestation d'intérêt désactivée avec succcès !"], Response::HTTP_OK
                    );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\ExpressionInterestType', $expressionInterest, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expressionInterestUnique = $repositoryExpressionInterest->findOneBy(array('reference' => $expressionInterest->getReference(), 'status' => 1));
            if ($expressionInterestUnique && $expressionInterestUnique->getId() != $expressionInterest->getId()) {
                return new JsonResponse(["success" => false, 'message' => "Une manifestation d'intérêt avec cette référence existe dejà"], Response::HTTP_NOT_FOUND);
            }            
            $expressionInterest->setAbstract($this->getAbstractOfExpressionInterest($expressionInterest));
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $expressionInterest->setState(1);
                } else {
                    $expressionInterest->setState(0);
                }
            }
            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            $expressionInterest_content_grid = $this->renderView('OGIVEAlertBundle:expressionInterest:expressionInterest-grid-edit.html.twig', array('expressionInterest' => $expressionInterest));
            $expressionInterest_content_list = $this->renderView('OGIVEAlertBundle:expressionInterest:expressionInterest-list-edit.html.twig', array('expressionInterest' => $expressionInterest));
            $view = View::create(["code" => 200, 'expressionInterest' => $expressionInterest, 'expressionInterest_content_grid' => $expressionInterest_content_grid, 'expressionInterest_content_list' => $expressionInterest_content_list]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_expressionInterest_form = $this->renderView('OGIVEAlertBundle:expressionInterest:edit.html.twig', array('form' => $form->createView(), 'expressionInterest' => $expressionInterest));
            $view = View::create(["code" => 200, 'expressionInterest' => $expressionInterest, 'edit_expressionInterest_form' => $edit_expressionInterest_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    public function getAbstractOfExpressionInterest(ExpressionInterest $expressionInterest){
        $contact = "+237694200310 / +237694202013";
        $dot = ".";
            if(substr(trim($expressionInterest->getObject()), -1) === "."){
                $dot = "";
            } 
        if($expressionInterest ){
            return $expressionInterest->getType()." : "."N°".$expressionInterest->getReference()." du ".date_format($expressionInterest->getPublicationDate(), "d/m/Y")." lancé par ".$expressionInterest->getOwner()." pour ".$expressionInterest->getObject().$dot." Dépôt des offres le ".date_format($expressionInterest->getOpeningDate(), "d/m/Y")." à ".date_format($expressionInterest->getOpeningDate(), "H:i").' \n'.$contact; 
        }else{
            return "";
        }
    }
}
