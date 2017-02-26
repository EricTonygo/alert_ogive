<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\SpecialFollowUp;
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
 * SpecialFollowUp controller.
 *
 */
class SpecialFollowUpController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/special-follow-ups" , name="specialFollowUp_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSpecialFollowUpsAction(Request $request) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $em = $this->getDoctrine()->getManager();
        $specialFollowUp = new SpecialFollowUp();
        $page=1;
        $maxResults = 4;
        $route_param_page= array();
        $route_param_search_query= array();
        $search_query =null;
        $placeholder = "Rechercher un suivi spécialisé...";
        if ($request->get('page')) {
            $page = intvalhtmlspecialchars(trim($request->get('page')));
            $route_param_page['page'] = $page;
        }
        if ($request->get('search_query')) {
            $search_query = htmlspecialchars(trim($request->get('search_query')));
            $route_param_search_query['search_query'] = $search_query;
        }
        $start_from = ($page-1)*$maxResults>=0 ? ($page-1)*$maxResults: 0;
        $total_pages = ceil(count($em->getRepository('OGIVEAlertBundle:SpecialFollowUp')->getAllByString($search_query))/$maxResults);
        $form = $this->createForm('OGIVE\AlertBundle\Form\SpecialFollowUpType', $specialFollowUp);
        $specialFollowUps = $em->getRepository('OGIVEAlertBundle:SpecialFollowUp')->getAll($start_from, $maxResults, $search_query);
        return $this->render('OGIVEAlertBundle:specialFollowUp:index.html.twig', array(
                    'specialFollowUps' => $specialFollowUps,
                    'total_pages' => $total_pages,
                    'page' => $page,
                    'form' => $form->createView(),
                    'route_param_page'=> $route_param_page, 
                    'route_param_search_query' => $route_param_search_query,
                    'search_query' => $search_query,
                    'placeholder' => $placeholder
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/special-follow-ups/{id}" , name="specialFollowUp_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getSpecialFollowUpByIdAction(SpecialFollowUp $specialFollowUp) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($specialFollowUp)) {
            return new JsonResponse(['message' => 'Suivi spécialisé introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\SpecialFollowUpType', $specialFollowUp, array('method' => 'PUT'));
        $specialFollowUp_details = $this->renderView('OGIVEAlertBundle:specialFollowUp:show.html.twig', array(
            'specialFollowUp' => $specialFollowUp,
            'form' => $form->createView()
        ));
        $view = View::create(['specialFollowUp_details' => $specialFollowUp_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/special-follow-ups", name="specialFollowUp_add", options={ "method_prefix" = false, "expose" = true  })
     */
    public function postSpecialFollowUpsAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $specialFollowUp = new SpecialFollowUp();
        $repositorySpecialFollowUp = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:SpecialFollowUp');
        $form = $this->createForm('OGIVE\AlertBundle\Form\SpecialFollowUpType', $specialFollowUp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositorySpecialFollowUp->findOneBy(array('name' => $specialFollowUp->getName(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Un suivi spécialisé avec ce nom existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $specialFollowUp->setState(1);
                }
            }
            $specialFollowUp = $repositorySpecialFollowUp->saveSpecialFollowUp($specialFollowUp);
//            $specialFollowUp_content_grid = $this->renderView('OGIVEAlertBundle:specialFollowUp:specialFollowUp-grid.html.twig', array('specialFollowUp' => $specialFollowUp));
//            $specialFollowUp_content_list = $this->renderView('OGIVEAlertBundle:specialFollowUp:specialFollowUp-list.html.twig', array('specialFollowUp' => $specialFollowUp));
//            $view = View::create(["code" => 200, 'specialFollowUp_content_grid' => $specialFollowUp_content_grid, 'specialFollowUp_content_list' => $specialFollowUp_content_list]);
            $view = View::create(["message" => 'Suivi spécialisé ajouté avec succès']);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'specialFollowUp' => $specialFollowUp, 'specialFollowUp_content_grid' => $specialFollowUp_content_grid, 'specialFollowUp_content_list' => $specialFollowUp_content_list], Response::HTTP_OK);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/special-follow-ups/{id}", name="specialFollowUp_delete", options={ "method_prefix" = false, "expose" = true  })
     */
    public function removeSpecialFollowUpAction(SpecialFollowUp $specialFollowUp) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositorySpecialFollowUp = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:SpecialFollowUp');
        if ($specialFollowUp) {
            $repositorySpecialFollowUp->deleteSpecialFollowUp($specialFollowUp);
            $view = View::create(["message" => 'Suivi spécialisé supprimé avec succès']);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => 'SpecialFollowUp introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/special-follow-ups/{id}", name="specialFollowUp_update", options={ "method_prefix" = false, "expose" = true  })
     * @param Request $request
     */
    public function putSpecialFollowUpAction(Request $request, SpecialFollowUp $specialFollowUp) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->updateSpecialFollowUpAction($request, $specialFollowUp);
    }

    public function updateSpecialFollowUpAction(Request $request, SpecialFollowUp $specialFollowUp) {

        $repositorySpecialFollowUp = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:SpecialFollowUp');

        if (empty($specialFollowUp)) {
            return new JsonResponse(['message' => 'Suivi spécialisé introuvable'], Response::HTTP_NOT_FOUND);
        }

        if ($request->get('action') == 'enable') {
            $specialFollowUp->setState(1);
            $specialFollowUp = $repositorySpecialFollowUp->updateSpecialFollowUp($specialFollowUp);
            return new JsonResponse(['message' => 'Suivi spécialisé activé avec succcès !'], Response::HTTP_OK
            );
        }

        if ($request->get('action') == 'disable') {
            $specialFollowUp->setState(0);
            $specialFollowUp = $repositorySpecialFollowUp->updateSpecialFollowUp($specialFollowUp);
            return new JsonResponse(['message' => 'Suivi spécialisé désactivé avec succcès !'], Response::HTTP_OK
            );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\SpecialFollowUpType', $specialFollowUp, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specialFollowUpUnique = $repositorySpecialFollowUp->findOneBy(array('name' => $specialFollowUp->getName(), 'status' => 1));
            if ($specialFollowUpUnique && $specialFollowUpUnique->getId() != $specialFollowUp->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Un suivi spécialisé avec ce nom existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $specialFollowUp->setState(1);
                } else {
                    $specialFollowUp->setState(0);
                }
            }
            $specialFollowUp = $repositorySpecialFollowUp->updateSpecialFollowUp($specialFollowUp);
//            $specialFollowUp_content_grid = $this->renderView('OGIVEAlertBundle:specialFollowUp:specialFollowUp-grid-edit.html.twig', array('specialFollowUp' => $specialFollowUp));
//            $specialFollowUp_content_list = $this->renderView('OGIVEAlertBundle:specialFollowUp:specialFollowUp-list-edit.html.twig', array('specialFollowUp' => $specialFollowUp));
//            $view = View::create(["code" => 200, 'specialFollowUp_content_grid' => $specialFollowUp_content_grid, 'specialFollowUp_content_list' => $specialFollowUp_content_list]);
            $view = View::create(["message" => 'Suivi spécialisé modifié avec succès']);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        } else {
            $edit_specialFollowUp_form = $this->renderView('OGIVEAlertBundle:specialFollowUp:edit.html.twig', array('form' => $form->createView(), 'specialFollowUp' => $specialFollowUp));
            $view = View::create(['edit_specialFollowUp_form' => $edit_specialFollowUp_form]);
            $view->setFormat('json');
            return $view;
        }
    }

}
