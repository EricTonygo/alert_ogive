<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Entreprise;
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
 * Entreprise controller.
 *
 */
class EntrepriseController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/entreprises" , name="entreprise_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getEntreprisesAction(Request $request) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $em = $this->getDoctrine()->getManager();
        $entreprise = new Entreprise();
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise);
        $entreprises = $em->getRepository('OGIVEAlertBundle:Entreprise')->getAll();
        return $this->render('OGIVEAlertBundle:entreprise:index.html.twig', array(
                    'entreprises' => $entreprises,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/entreprises/{id}" , name="entreprise_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getEntrepriseByIdAction(Entreprise $entreprise) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($entreprise)) {
            return new JsonResponse(['message' => 'Entreprise introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise, array('method' => 'PUT'));
        $entreprise_details = $this->renderView('OGIVEAlertBundle:entreprise:show.html.twig', array(
            'entreprise' => $entreprise,
            'form' => $form->createView()
        ));
        $view = View::create(["code" => 200, 'entreprise' => $entreprise, 'entreprise_details' => $entreprise_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/entreprises", name="entreprise_add", options={ "method_prefix" = false, "expose" = true  })
     */
    public function postEntreprisesAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $entreprise = new Entreprise();
        $repositoryEntreprise = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Entreprise');
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositoryEntreprise->findOneBy(array('name' => $entreprise->getName(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Une entreprise avec ce nom existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $entreprise->setState(1);
                $subscribers = $entreprise->getSubscribers();
                foreach ($subscribers as $subscriber) {
                    $subscriber->setState(1);
                }
            }
            $entreprise = $repositoryEntreprise->saveEntreprise($entreprise);
            $entreprise_content_grid = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-grid.html.twig', array('entreprise' => $entreprise));
            $entreprise_content_list = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-list.html.twig', array('entreprise' => $entreprise));
            $view = View::create(["code" => 200, 'entreprise' => $entreprise, 'entreprise_content_grid' => $entreprise_content_grid, 'entreprise_content_list' => $entreprise_content_list]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'entreprise' => $entreprise, 'entreprise_content_grid' => $entreprise_content_grid, 'entreprise_content_list' => $entreprise_content_list], Response::HTTP_OK);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/entreprises/{id}", name="entreprise_delete", options={ "method_prefix" = false, "expose" = true  })
     */
    public function removeEntrepriseAction(Entreprise $entreprise) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositoryEntreprise = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Entreprise');
        if ($entreprise) {
            $repositoryEntreprise->deleteEntreprise($entreprise);
            $view = View::create(['entreprise' => $entreprise, "message" => 'Entreprise supprimée avec succès']);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => 'Entreprise introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/entreprises/{id}", name="entreprise_update", options={ "method_prefix" = false, "expose" = true  })
     * @param Request $request
     */
    public function putEntrepriseAction(Request $request, Entreprise $entreprise) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->updateEntrepriseAction($request, $entreprise);
    }

    public function updateEntrepriseAction(Request $request, Entreprise $entreprise) {

        $repositoryEntreprise = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Entreprise');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');

        if (empty($entreprise)) {
            return new JsonResponse(['message' => 'Entreprise introuvable'], Response::HTTP_NOT_FOUND);
        }

        if ($request->get('action') == 'enable') {
            $entreprise->setState(1);
            $subscribers = $entreprise->getSubscribers();
            foreach ($subscribers as $subscriber) {
                $subscriber->setState(1);
                $repositorySubscriber->updateSubscriber($subscriber);
            }
            $entreprise = $repositoryEntreprise->updateEntreprise($entreprise);
            return new JsonResponse(['message' => 'Entreprise activée avec succcès !'], Response::HTTP_OK
            );
        }

        if ($request->get('action') == 'disable') {
            $entreprise->setState(0);
            $subscribers = $entreprise->getSubscribers();
            foreach ($subscribers as $subscriber) {
                $subscriber->setState(0);
                $repositorySubscriber->updateSubscriber($subscriber);
            }
            $entreprise = $repositoryEntreprise->updateEntreprise($entreprise);
            return new JsonResponse(['message' => 'Entreprise désactivée avec succcès !'], Response::HTTP_OK
            );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entrepriseUnique = $repositoryEntreprise->findOneBy(array('name' => $entreprise->getName(), 'status' => 1));
            if ($entrepriseUnique && $entrepriseUnique->getId() != $entreprise->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Une entreprise avec ce nom existe dejà'], Response::HTTP_NOT_FOUND);
            }
            $entreprise = $repositoryEntreprise->updateEntreprise($entreprise);
            $entreprise_content_grid = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-grid-edit.html.twig', array('entreprise' => $entreprise));
            $entreprise_content_list = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-list-edit.html.twig', array('entreprise' => $entreprise));
            $view = View::create(["code" => 200, 'entreprise' => $entreprise, 'entreprise_content_grid' => $entreprise_content_grid, 'entreprise_content_list' => $entreprise_content_list]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_entreprise_form = $this->renderView('OGIVEAlertBundle:entreprise:edit.html.twig', array('form' => $form->createView(), 'entreprise' => $entreprise));
            $view = View::create(["code" => 200, 'entreprise' => $entreprise, 'edit_entreprise_form' => $edit_entreprise_form]);
            $view->setFormat('json');
            return $view;
        }
    }

}
