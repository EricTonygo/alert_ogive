<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Subscriber;
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
use Twilio\Rest\Client;

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
        $repositorySubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscription');
        $subscriber = new Subscriber();
        $page = 1;
        $maxResults = 8;
        $route_param_page = array();
        $route_param_search_query = array();
        $search_query = null;
        $placeholder = "Rechercher un abonné...";
        if ($request->get('page')) {
            $page = intval(htmlspecialchars(trim($request->get('page'))));
            $route_param_page['page'] = $page;
        }
        if ($request->get('search_query')) {
            $search_query = htmlspecialchars(trim($request->get('search_query')));
            $route_param_search_query['search_query'] = $search_query;
        }
        $start_from = ($page - 1) * $maxResults >= 0 ? ($page - 1) * $maxResults : 0;
        $total_pages = ceil(count($em->getRepository('OGIVEAlertBundle:Subscriber')->getAllByString($search_query)) / $maxResults);
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber);
        $subscribers = $em->getRepository('OGIVEAlertBundle:Subscriber')->getAll($start_from, $maxResults, $search_query);
        $subscriptions = $repositorySubscription->findBy(array('status' => 1, 'state' => 1));
        return $this->render('OGIVEAlertBundle:subscriber:index.html.twig', array(
                    'subscribers' => $subscribers,
                    'total_pages' => $total_pages,
                    'page' => $page,
                    'form' => $form->createView(),
                    'route_param_page' => $route_param_page,
                    'route_param_search_query' => $route_param_search_query,
                    'search_query' => $search_query,
                    'placeholder' => $placeholder,
                    'subscriptions' => $subscriptions
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
        $view = View::create(['subscriber_details' => $subscriber_details]);
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
        $historicalSubscriberSubscription = new \OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription();
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $repositoryHistoricalSubscriberSubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalSubscriberSubscription');
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Un abonné avec ce numero existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN') && $subscriber->getSubscription()) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on' && $subscriber->getEntreprise() && $subscriber->getEntreprise()->getState() == 1) {
                    $subscriber->setState(1);
                } else {
                    $subscriber->setState(0);
                }
            }
            if ($subscriber->getSubscription() && $subscriber->getState() == 1) {
                $subscriber->setLastSubscriptionDate(new \DateTime('now'));
            }
            $subscriber = $repositorySubscriber->saveSubscriber($subscriber);
            $curl_response = null;
            if ($subscriber->getSubscription() && $subscriber->getState() == 1) {
                $historicalSubscriberSubscription->setSubscriber($subscriber);
                $historicalSubscriberSubscription->setSubscription($subscriber->getSubscription());
                $historicalSubscriberSubscription->setSubscriptionDateAndExpirationDate($subscriber->getLastSubscriptionDate());
                $historicalSubscriberSubscription = $repositoryHistoricalSubscriberSubscription->saveHistoricalSubscriberSubscription($historicalSubscriberSubscription);
                $curl_response = json_decode($this->get('curl_service')->createSubscriberAccount($subscriber), true);
                if ($curl_response['success'] == true) {
                    $this->get('mail_service')->sendMailForWebsiteAccount($curl_response['data']['email'], $curl_response['data']['subject'], $curl_response['data']['message']);
                }
            }
            $sendConfirmation = $request->get('send_confirmation');
            if ($sendConfirmation && $sendConfirmation === 'on') {
                if ($subscriber->getSubscription() && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $this->sendSubscriptionConfirmation($subscriber);
                }
            }

//            $subscriber_content_grid = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-grid.html.twig', array('subscriber' => $subscriber));
//            $subscriber_content_list = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-list.html.twig', array('subscriber' => $subscriber));
//            $view = View::create(["code" => 200, 'subscriber_content_grid' => $subscriber_content_grid, 'subscriber_content_list' => $subscriber_content_list]);
            $view = View::create(["message" => 'Abonné ajouté avec succès', "curl_response" => $curl_response]);
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
            $view = View::create(["message" => 'Abonné supprimé avec succès']);
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
        $historicalSubscriberSubscription = new \OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription();
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $repositorySubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscription');
        $repositoryHistoriqueSubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositoryHistoricalSubscriberSubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalSubscriberSubscription');
        $oldSubscription = $subscriber->getSubscription();
        //$this = new TelephoneController();
        if (empty($subscriber)) {
            return new JsonResponse(['message' => 'Abonné introuvable'], Response::HTTP_NOT_FOUND);
        }
        $historiqueAlertSubscriber = null;
        if ($request->get('action') == 'enable') {
            if ($subscriber->getSubscription() && $subscriber->getEntreprise()->getState() == 1) {
                $subscriber->setState(1);
                $subscription_update = $request->get('subscription_update');
                if ($subscription_update && $subscription_update != 'others') {
                    $subscriber->setLastSubscriptionDate(new \DateTime('now'));
                }
                $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
                $curl_response = $this->get('curl_service')->enableSubscriberAccount($subscriber, $subscriber->getExpiredState());
                if ($subscription_update && $subscription_update != 'others') {
                    $historicalSubscriberSubscription->setSubscriber($subscriber);
                    $historicalSubscriberSubscription->setSubscription($subscriber->getSubscription());
                    $historicalSubscriberSubscription->setSubscriptionDateAndExpirationDate($subscriber->getLastSubscriptionDate());
                    $historicalSubscriberSubscription = $repositoryHistoricalSubscriberSubscription->saveHistoricalSubscriberSubscription($historicalSubscriberSubscription);
                    $historiqueAlertSubscriber = $this->sendRenewalSubscriptionConfirmation($subscriber, $subscription_update);
                }
                return new JsonResponse(['message' => 'Abonné activé avec succcès !', 'curl_response' => $curl_response], Response::HTTP_OK
                );
            } else {
                if ($subscriber->getEntreprise()->getState() == 0) {
                    return new JsonResponse(['message' => "Activation impossible car l'entreprise de cet abonné est désactivée."], Response::HTTP_NOT_FOUND);
                } elseif ($subscriber->getSubscription() == null) {
                    return new JsonResponse(['message' => "Activation impossible car cet abonné n'a pas d'abonnement."], Response::HTTP_NOT_FOUND);
                } else {
                    return new JsonResponse(['message' => "Impossible d'activer cet abonné"], Response::HTTP_NOT_FOUND);
                }
            }
        }
        $action = $request->get('action');
        if ($action == 'renewal-subscription') {
            if ($subscriber->getEntreprise() && $subscriber->getEntreprise()->getState() == 1) {
                $subscriber->setLastSubscriptionDate(new \DateTime(date('Y-m-d H:i:s', strtotime($request->get('renewal_subscription_subscriber_date')))));
                $subscriber->setSubscription($repositorySubscription->find(intval($request->get('subscription_type'))));
                $curl_response = null;
                if ($subscriber->getSubscription()) {
                    $subscriber->setState(1);
                    $subscriber->setExpiredState(0);
                }
                $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
                if ($subscriber->getSubscription() && $subscriber->getState() == 1) {
                    $curl_response = $this->get('curl_service')->enableSubscriberAccount($subscriber, $subscriber->getExpiredState());
                }
                $historicalSubscriberSubscription->setSubscriber($subscriber);
                $historicalSubscriberSubscription->setSubscription($subscriber->getSubscription());
                $historicalSubscriberSubscription->setSubscriptionDateAndExpirationDate($subscriber->getLastSubscriptionDate());
                $historicalSubscriberSubscription = $repositoryHistoricalSubscriberSubscription->saveHistoricalSubscriberSubscription($historicalSubscriberSubscription);
                $sendRenewalNotification = $request->get('send_renewal_notification');
                if ($sendRenewalNotification == "on") {
                    $historiqueAlertSubscriber = $this->sendRenewalSubscriptionConfirmation($subscriber, $action);
                }
                return new JsonResponse(['message' => 'Abonnement renouvelé avec succcès !', 'curl_response' => $curl_response], Response::HTTP_OK
                );
            } else {
                if ($subscriber->getEntreprise()->getState() == 0) {
                    return new JsonResponse(['message' => "Activation impossible car l'entreprise de cet abonné est désactivée."], Response::HTTP_NOT_FOUND);
                } else {
                    return new JsonResponse(['message' => "Impossible d'activer cet abonné"], Response::HTTP_NOT_FOUND);
                }
            }
        }


        if ($request->get('action') == 'disable') {
            $subscriber->setState(0);
            $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
            $curl_response = $this->get('curl_service')->disableSubscriberAccount($subscriber, $subscriber->getExpiredState());
            return new JsonResponse(['message' => 'Abonné désactivé avec succcès !', "curl_response" => $curl_response], Response::HTTP_OK
            );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriberUnique = $repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1));
            if ($subscriberUnique && $subscriberUnique->getId() != $subscriber->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Un abonné avec ce numero existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN') && $subscriber->getSubscription()) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on' && $subscriber->getEntreprise()->getState() == 1) {
                    $subscriber->setState(1);
                } else {
                    $subscriber->setState(0);
                }
            }
            $subscription_update = $request->get('subscription_update');
            if ($subscription_update && $subscription_update != 'others') {
                $subscriber->setLastSubscriptionDate(new \DateTime('now'));
                if ($subscriber->getSubscription()) {
                    $subscriber->setState(1);
                    $subscriber->setExpiredState(0);
                }
            }
            $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
            $curl_response = null;
            if ($subscriber->getSubscription() && $subscriber->getState() == 1) {
                $curl_response = json_decode($this->get('curl_service')->updateSubscriberAccount($subscriber), true);
//                if ($curl_response['success'] == true) {
//                    $this->get('mail_service')->sendMail($curl_response['data']['email'], $curl_response['data']['subject'], $curl_response['data']['message']);
//                }
            }
            if ($subscription_update && $subscription_update != 'others') {
                if ($subscriber->getSubscription() && $subscriber->getState() == 1) {
                    $historicalSubscriberSubscription->setSubscriber($subscriber);
                    $historicalSubscriberSubscription->setSubscription($subscriber->getSubscription());
                    $historicalSubscriberSubscription->setSubscriptionDateAndExpirationDate($subscriber->getLastSubscriptionDate());
                    $historicalSubscriberSubscription = $repositoryHistoricalSubscriberSubscription->saveHistoricalSubscriberSubscription($historicalSubscriberSubscription);
                    $historiqueAlertSubscriber = $this->sendRenewalSubscriptionConfirmation($subscriber, $subscription_update);
                }
            }
            $sendConfirmation = $request->get('send_confirmation');
            if ($historiqueAlertSubscriber === null && $sendConfirmation && $sendConfirmation === 'on') {
                if ($oldSubscription === null && $subscriber->getSubscription() && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $historiqueAlertSubscriber = $this->sendSubscriptionConfirmation($subscriber);
                } elseif ($oldSubscription && $subscriber->getSubscription() && $oldSubscription->getId() != $subscriber->getSubscription()->getId() && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $historiqueAlertSubscriber = $this->sendSubscriptionConfirmation($subscriber);
                } elseif ($subscriber->getSubscription() && $repositoryHistoriqueSubscriber->findBy(array('subscriber' => $subscriber, 'alertType' => "SMS_CONFIRMATION_SUBSCRIPTION", "status" => 1)) == null && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $historiqueAlertSubscriber = $this->sendSubscriptionConfirmation($subscriber);
                }
            }

            $view = View::create(["message" => 'Abonné modifié avec succès', "curl_response" => $curl_response]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_subscriber_form = $this->renderView('OGIVEAlertBundle:subscriber:edit.html.twig', array('form' => $form->createView(), 'subscriber' => $subscriber));
            $view = View::create(['edit_subscriber_form' => $edit_subscriber_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View()
     * @Rest\Post("/send-subscription-confirmation/{id}", name="send_subscription_confirmation_post", options={ "method_prefix" = false, "expose" = true  })
     */
    public function postSendSubcriptionConfirmationAction(Request $request, Subscriber $subscriber) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($subscriber)) {
            return new JsonResponse(['message' => 'Abonné introuvable'], Response::HTTP_NOT_FOUND);
        }
        $historical = $this->sendSubscriptionConfirmation($subscriber);
        if (empty($historical)) {
            return new JsonResponse(['message' => "Erreur lors de l'envoi du message"], Response::HTTP_NOT_FOUND);
        }
        $view = View::create(['message' => "Accusé de reception envoyé avec succès"]);
        $view->setFormat('json');
        return $view;
    }

    public function sendSubscriptionConfirmation(Subscriber $subscriber) {
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $cout = $subscriber->getSubscriptionCostAndValidity();
        $content_email = $subscriber->getEntreprise()->getName() . ", votre souscription au service <<APPELS D'OFFRES INFOS>> a été éffectuée avec succès. \nCoût du forfait = " . $cout;
        $content_sms = $subscriber->getEntreprise()->getName() . ", votre souscription au service APPELS D'OFFRES INFOS a ete effectuee avec succes. Cout du forfait = " . $cout;
        $this->sendNotificationAccordingToType($subscriber, "CONFIRMATION DE L'ABONNEMENT", $content_email, $content_sms);
        $historiqueAlertSubscriber->setMessage($content_email);
        $historiqueAlertSubscriber->setSubscriber($subscriber);
        $historiqueAlertSubscriber->setAlertType("SMS_CONFIRMATION_SUBSCRIPTION");
        return $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
    }

    public function sendRenewalSubscriptionConfirmation(Subscriber $subscriber, $subscription_update) {
        if ($subscription_update && $subscription_update === "renewal-subscription") {
            $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
            $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
            $cout = $subscriber->getSubscriptionCostAndValidity();
            $content_email = $subscriber->getEntreprise()->getName() . ", votre abonnement au service <<APPELS D'OFFRES INFOS>> a été renouvelé avec succès. \nCoût du nouveau forfait = " . $cout;
            $content_sms = $subscriber->getEntreprise()->getName() . ", votre abonnement au service APPELS D'OFFRES INFOS a ete renouvele avec succes. Cout du nouveau forfait = " . $cout;
            $this->sendNotificationAccordingToType($subscriber, "RENOUVELLEMENT DE L'ABONNEMENT", $content_email, $content_sms);
            $historiqueAlertSubscriber->setMessage($content_email);
            $historiqueAlertSubscriber->setSubscriber($subscriber);
            $historiqueAlertSubscriber->setAlertType("SMS_CONFIRMATION_RENEWAL_SUBSCRIPTION");
            return $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
        } else {
            return null;
        }
    }

    public function sendNotificationAccordingToType(Subscriber $subscriber, $subject, $message_email, $message_sms) {
        //Backup:  "SI OGIVE vous remercie pour votre confiance."
        if ($subscriber->getNotificationType() == 2) {
            $this->get('sms_service')->sendSms($subscriber->getPhoneNumber(), $message_sms);
        } elseif ($subscriber->getNotificationType() == 1) {
            $this->get('mail_service')->sendEmailSubscriber($subscriber, $subject, $message_email . " SI OGIVE vous remercie pour votre confiance.");
        } else {
            $this->get('sms_service')->sendSms($subscriber->getPhoneNumber(), $message_sms);
            $this->get('mail_service')->sendEmailSubscriber($subscriber, $subject, $message_email . " SI OGIVE vous remercie pour votre confiance.");
        }
    }

}
