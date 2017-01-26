<?php

namespace OGIVE\AlertBundle\Controller;

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

/**
 * Subscriber controller.
 *
 */
class SubscriberController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/subscribers" , name="subscriber_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSubscribersAction(Request $request) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        $em = $this->getDoctrine()->getManager();
        $subscriber = new Subscriber();
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber);
        $subscribers = $em->getRepository('OGIVEAlertBundle:Subscriber')->getAll();
        return $this->render('OGIVEAlertBundle:subscriber:index.html.twig', array(
                    'subscribers' => $subscribers,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/subscribers/{id}" , name="subscriber_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getSubscriberByIdAction(Subscriber $subscriber) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($subscriber)) {
            return new JsonResponse(['message' => 'Abonné introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber, array('method' => 'PUT'));
        $subscriber_details = $this->renderView('OGIVEAlertBundle:subscriber:show.html.twig', array(
            'subscriber' => $subscriber,
            'form' => $form->createView()
        ));
        $view = View::create(["code" => 200, 'subscriber' => $subscriber, 'subscriber_details' => $subscriber_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/subscribers", name="subscriber_add", options={ "method_prefix" = false, "expose" = true  })
     */
    public function postSubscribersAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $subscriber = new Subscriber();
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Un abonné avec ce numero existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN') && $subscriber->getSubscription()) {
                $subscriber->setState(1);
            }
            $subscriber = $repositorySubscriber->saveSubscriber($subscriber);
            $subscriber_content_grid = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-grid.html.twig', array('subscriber' => $subscriber));
            $subscriber_content_list = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-list.html.twig', array('subscriber' => $subscriber));
            $view = View::create(["code" => 200, 'subscriber' => $subscriber, 'subscriber_content_grid' => $subscriber_content_grid, 'subscriber_content_list' => $subscriber_content_list]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'subscriber' => $subscriber, 'subscriber_content_grid' => $subscriber_content_grid, 'subscriber_content_list' => $subscriber_content_list], Response::HTTP_OK);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/subscribers/{id}", name="subscriber_delete", options={ "method_prefix" = false, "expose" = true  })
     */
    public function removeSubscriberAction(Subscriber $subscriber) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        if ($subscriber) {
            $repositorySubscriber->deleteSubscriber($subscriber);
            $view = View::create(['subscriber' => $subscriber, "message" => 'Abonné supprimé avec succès']);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => 'Abonné introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/subscribers/{id}", name="subscriber_update", options={ "method_prefix" = false, "expose" = true  })
     * @param Request $request
     */
    public function putSubscriberAction(Request $request, Subscriber $subscriber) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->updateSubscriberAction($request, $subscriber);
    }

    public function updateSubscriberAction(Request $request, Subscriber $subscriber) {

        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');

        if (empty($subscriber)) {
            return new JsonResponse(['message' => 'Abonné introuvable'], Response::HTTP_NOT_FOUND);
        }

        if ($request->get('action') == 'enable') {
            if ($subscriber->getSubscription()) {
                $subscriber->setState(1);
                $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
                return new JsonResponse(['message' => 'Abonné activé avec succcès !'], Response::HTTP_OK
                );
            }else{
                return new JsonResponse(['message' => "Cet abonné n'a pas souscrit à un abonnement"], Response::HTTP_NOT_FOUND);
            }
        }

        if ($request->get('action') == 'disable') {
            $subscriber->setState(0);
            $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
            return new JsonResponse(['message' => 'Abonné désactivé avec succcès !'], Response::HTTP_OK
            );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriberUnique = $repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1));
            if ($subscriberUnique && $subscriberUnique->getId() != $subscriber->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Un abonné avec ce numero existe dejà'], Response::HTTP_NOT_FOUND);
            }
            $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
            $subscriber_content_grid = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-grid-edit.html.twig', array('subscriber' => $subscriber));
            $subscriber_content_list = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-list-edit.html.twig', array('subscriber' => $subscriber));
            $view = View::create(["code" => 200, 'subscriber' => $subscriber, 'subscriber_content_grid' => $subscriber_content_grid, 'subscriber_content_list' => $subscriber_content_list]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_subscriber_form = $this->renderView('OGIVEAlertBundle:subscriber:edit.html.twig', array('form' => $form->createView(), 'subscriber' => $subscriber));
            $view = View::create(["code" => 200, 'subscriber' => $subscriber, 'edit_subscriber_form' => $edit_subscriber_form]);
            $view->setFormat('json');
            return $view;
        }
    }

}
