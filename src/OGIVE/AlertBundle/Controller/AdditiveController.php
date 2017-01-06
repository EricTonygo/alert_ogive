<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Additive;
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
 * Additive controller.
 *
 */
class AdditiveController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/additives" , name="additive_index", options={ "method_prefix" = false })
     * @param Request $request
     */
    public function getAdditivesAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $additive = new Additive();
        $form = $this->createForm('OGIVE\AlertBundle\Form\AdditiveType', $additive);
        $additives = $em->getRepository('OGIVEAlertBundle:Additive')->getAll();
        return $this->render('OGIVEAlertBundle:additive:index.html.twig', array(
                    'additives' => $additives,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/additives")
     */
    public function postAdditivesAction(Request $request) {
        $additive = new Additive();
        $repositoryAdditive = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Additive');
        $form = $this->createForm('OGIVE\AlertBundle\Form\AdditiveType', $additive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($repositoryAdditive->findOneBy(array('name' => $additive->getName(), 'status' => 1))){
                return new JsonResponse(["success" => false, 'message' => 'Un additif avec ce nom existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            $additive = $repositoryAdditive->saveAdditive($additive);
            $additve_content_grid = $this->renderView('OGIVEAlertBundle:additive:additive-grid.html.twig', array('additive' => $additive));
            $additive_content_list = $this->renderView('OGIVEAlertBundle:additive:additive-list.html.twig', array('additive' => $additive));
            /* @var $additive Additive */
            $view = View::create(["code" => 200, 'additive' => $additive, 'additive_content_grid' => $additive_content_grid, 'additive_content_list' => $additive_content_list]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'additive' => $additive, 'additive_content_grid' => $additive_content_grid, 'additive_content_list' => $additive_content_list], Response::HTTP_OK);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/additives/{id}")
     */
    public function removeAdditiveAction(Additive $additive) {

        $repositoryAdditive = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Additive');
        if ($additive) {
            $repositoryAdditive->deleteAdditive($additive);
            $view = View::create(['additive' => $additive, "message" => 'Additif supprimé avec succès']);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => 'Additif introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/additives/{id}", name="additive_update", options={ "method_prefix" = false })
     * @param Request $request
     */
    public function putAdditiveAction(Request $request, Additive $additive) {

        return $this->updateAdditiveAction($request, $additive);
    }

    
    public function updateAdditiveAction(Request $request, Additive $additive) {

        $repositoryAdditive = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Additive');

        if (empty($additive)) {
            return new JsonResponse(['message' => 'Additif introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\AdditiveType', $additive, array('method' => 'PUT'));

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $additiveUnique = $repositoryAdditive->findOneBy(array('name' => $additive->getName(), 'status' => 1));
            if($additiveUnique && $additiveUnique->getId() != $additive->getId()){
                return new JsonResponse(["success" => false, 'message' => 'Un additif avec ce nom existe dejà'], Response::HTTP_NOT_FOUND);
            }
            $additive = $repositoryAdditive->updateAdditive($additive);
            $additive_content_grid = $this->renderView('OGIVEAlertBundle:additive:additive-grid-edit.html.twig', array('additive' => $additive));
            $additive_content_list = $this->renderView('OGIVEAlertBundle:additive:additive-list-edit.html.twig', array('additive' => $additive));
            /* @var $additive Additive */
            $view = View::create(["code" => 200, 'additive' => $additive, 'additive_content_grid' => $additive_content_grid, 'additive_content_list' => $additive_content_list]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_additive_form = $this->renderView('OGIVEAlertBundle:additive:edit.html.twig', array('form' => $form->createView(), 'additive' => $additive));
            /* @var $additive Additive */
            $view = View::create(["code" => 200, 'additive' => $additive, 'edit_additive_form' => $edit_additive_forma_form]);
            $view->setFormat('json');
            return $view;
        }
    }
}
