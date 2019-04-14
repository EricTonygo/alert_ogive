<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Subscription;
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
 * Subscription controller.
 *
 */
class SubscriptionController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/subscriptions" , name="subscription_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSubscriptionsAction(Request $request) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $subscription = new Subscription();
        $page=1;
        $maxResults = 8;
        $route_param_page= array();
        $route_param_search_query= array();
        $search_query =null;
        $placeholder = "Rechercher un abonnement...";
        if ($request->get('page')) {
            $page = intval(htmlspecialchars(trim($request->get('page'))));
            $route_param_page['page'] = $page;
        }
        if ($request->get('search_query')) {
            $search_query = htmlspecialchars(trim($request->get('search_query')));
            $route_param_search_query['search_query'] = $search_query;
        }
        $start_from = ($page-1)*$maxResults>=0 ? ($page-1)*$maxResults: 0;
        $total_pages = ceil(count($em->getRepository('OGIVEAlertBundle:Subscription')->getAllByString($search_query))/$maxResults);
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriptionType', $subscription);
        $subscriptions = $em->getRepository('OGIVEAlertBundle:Subscription')->getAll($start_from, $maxResults, $search_query);
        return $this->render('OGIVEAlertBundle:subscription:index.html.twig', array(
                    'subscriptions' => $subscriptions,
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
     * @Rest\Get("/subscriptions/{id}" , name="subscription_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getSubscriptionByIdAction(Subscription $subscription) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($subscription)) {
            return new JsonResponse(['message' => 'Abonnement introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriptionType', $subscription, array('method' => 'PUT'));
        $subscription_details = $this->renderView('OGIVEAlertBundle:subscription:show.html.twig', array(
            'subscription' => $subscription,
            'form' => $form->createView()
        ));
        $view = View::create(['subscription_details' => $subscription_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/subscriptions", name="subscription_add", options={ "method_prefix" = false, "expose" = true  })
     */
    public function postSubscriptionsAction(Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $subscription = new Subscription();
        $repositorySubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscription');
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriptionType', $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositorySubscription->findOneBy(array('name' => $subscription->getName(),'periodicity' => $subscription->getPeriodicity(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Un abonnement avec ce nom et cette périodicité existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $subscription->setState(1);
            }
            
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $subscription->setState(1);
                }
            }
            
            $subscription = $repositorySubscription->saveSubscription($subscription);
//            $subscription_content_grid = $this->renderView('OGIVEAlertBundle:subscription:subscription-grid.html.twig', array('subscription' => $subscription));
//            $subscription_content_list = $this->renderView('OGIVEAlertBundle:subscription:subscription-list.html.twig', array('subscription' => $subscription));
//            $view = View::create(["code" => 200, 'subscription_content_grid' => $subscription_content_grid, 'subscription_content_list' => $subscription_content_list]);
            $view = View::create(["message" => 'Abonnement ajouté avec succès']);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'subscription' => $subscription, 'subscription_content_grid' => $subscription_content_grid, 'subscription_content_list' => $subscription_content_list], Response::HTTP_OK);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/subscriptions/{id}", name="subscription_delete", options={ "method_prefix" = false, "expose" = true  })
     */
    public function removeSubscriptionAction(Subscription $subscription) {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositorySubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscription');
        if ($subscription) {
            $repositorySubscription->deleteSubscription($subscription);
            $view = View::create(["message" => 'Abonnement supprimé avec succès']);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => 'Subscription introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/subscriptions/{id}", name="subscription_update", options={ "method_prefix" = false, "expose" = true  })
     * @param Request $request
     */
    public function putSubscriptionAction(Request $request, Subscription $subscription) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->updateSubscriptionAction($request, $subscription);
    }

    public function updateSubscriptionAction(Request $request, Subscription $subscription) {

        $repositorySubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscription');

        if (empty($subscription)) {
            return new JsonResponse(['message' => 'Abonnement introuvable'], Response::HTTP_NOT_FOUND);
        }
        
        if($request->get('action')== 'enable'){
            $subscription->setState(1);
            $subscription = $repositorySubscription->updateSubscription($subscription);
            return new JsonResponse(['message' => 'Abonnement activé avec succcès !'], Response::HTTP_OK
                    );
        }
        
        if($request->get('action')== 'disable'){
            $subscription->setState(0);
            $subscription = $repositorySubscription->updateSubscription($subscription);
            return new JsonResponse(['message' => 'Abonnement désactivé avec succcès !'], Response::HTTP_OK
                    );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriptionType', $subscription, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriptionUnique = $repositorySubscription->findOneBy(array('name' => $subscription->getName(),'periodicity' => $subscription->getPeriodicity(), 'status' => 1));
            if ($subscriptionUnique && $subscriptionUnique->getId() != $subscription->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Un abonnement avec ce nom et cette périodicité existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $subscription->setState(1);
                } else {
                    $subscription->setState(0);
                }
            }
            
            $subscription = $repositorySubscription->updateSubscription($subscription);
//            $subscription_content_grid = $this->renderView('OGIVEAlertBundle:subscription:subscription-grid-edit.html.twig', array('subscription' => $subscription));
//            $subscription_content_list = $this->renderView('OGIVEAlertBundle:subscription:subscription-list-edit.html.twig', array('subscription' => $subscription));
//            $view = View::create(["code" => 200, 'subscription_content_grid' => $subscription_content_grid, 'subscription_content_list' => $subscription_content_list]);
            $view = View::create(["message" => 'Abonnement modifié avec succès']);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_subscription_form = $this->renderView('OGIVEAlertBundle:subscription:edit.html.twig', array('form' => $form->createView(), 'subscription' => $subscription));
            $view = View::create(['edit_subscription_form' => $edit_subscription_form]);
            $view->setFormat('json');
            return $view;
        }
    }

}
