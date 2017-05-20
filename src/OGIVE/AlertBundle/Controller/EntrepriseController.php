<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Entreprise;
use OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber;
use OGIVE\AlertBundle\Entity\HistoricalSubscriberSubscription;
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
        $page = 1;
        $maxResults = 8;
        $route_param_page = array();
        $route_param_search_query = array();
        $search_query = null;
        $placeholder = "Rechercher une entreprise...";
        if ($request->get('page')) {
            $page = intval(htmlspecialchars(trim($request->get('page'))));
            $route_param_page['page'] = $page;
        }
        if ($request->get('search_query')) {
            $search_query = htmlspecialchars(trim($request->get('search_query')));
            $route_param_search_query['search_query'] = $search_query;
        }
        $start_from = ($page - 1) * $maxResults >= 0 ? ($page - 1) * $maxResults : 0;
        $total_pages = ceil(count($em->getRepository('OGIVEAlertBundle:Entreprise')->getAllByString($search_query)) / $maxResults);
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise);
        $entreprises = $em->getRepository('OGIVEAlertBundle:Entreprise')->getAll($start_from, $maxResults, $search_query);
        return $this->render('OGIVEAlertBundle:entreprise:index.html.twig', array(
                    'entreprises' => $entreprises,
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
     * @Rest\Get("/entreprises/{id}" , name="entreprise_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getEntrepriseByIdAction(Entreprise $entreprise) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $serializer = $this->container->get('jms_serializer');
        if (empty($entreprise)) {
            return new JsonResponse(['message' => 'Entreprise introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise, array('method' => 'PUT'));
        $entreprise_details = $this->renderView('OGIVEAlertBundle:entreprise:show.html.twig', array(
            'entreprise' => $entreprise,
            'form' => $form->createView()
        ));
        $view = View::create(['entreprise_details' => $entreprise_details]);
        $view->setFormat('json');
        return $view;
        //return new JsonResponse(["code" => 200, 'entreprise_details' => $entreprise_details], Response::HTTP_OK);
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
        //$this = new TelephoneController();
        $repositoryEntreprise = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Entreprise');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $repositoryHistoricalSubscriberSubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalSubscriberSubscription');
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise);
        $serializer = $this->container->get('jms_serializer');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositoryEntreprise->findOneBy(array('phoneNumber' => $entreprise->getAddress()->getPhone(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Une entreprise avec ce numéro de téléphone existe dejà'], Response::HTTP_BAD_REQUEST);
            }


            $sendActivate = $request->get('send_activate');
            if ($sendActivate && $sendActivate === 'on') {
                $entreprise->setState(1);
            }
            //*************** gestion des domaines de l'entreprise **************************/
            $domains = $entreprise->getDomains();
            foreach ($domains as $domain) {
                $domain->addEntreprise($entreprise);
            }
            //*************** gestion des sous-domaines de l'entreprise **************************/
            $subDomains = $entreprise->getSubDomains();
            foreach ($subDomains as $subDomain) {
                $subDomain->addEntreprise($entreprise);
            }
            //***************gestion des abonnés de l'entreprise ************************** */
            $subscribers = $entreprise->getSubscribers();
            foreach ($subscribers as $subscriber) {
                if ($subscriber->getName() == null || $subscriber->getName() == "") {
                    return new JsonResponse(["success" => false, 'message' => "Vous avez des abonnés sans noms. Vueillez les remplir. "], Response::HTTP_BAD_REQUEST);
                }
                if ($subscriber->getPhoneNumber() == null || $subscriber->getPhoneNumber() == "") {
                    return new JsonResponse(["success" => false, 'message' => "Vous avez des abonnés sans numéros de téléphone. Vueillez les remplir. "], Response::HTTP_BAD_REQUEST);
                }

                if ($subscriber->getSubscription() == null) {
                    return new JsonResponse(["success" => false, 'message' => "Vous avez des abonnés sans abonnement. Veuillez leur affecter un abonnement."], Response::HTTP_BAD_REQUEST);
                }

                $subscriberUnique = $repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1));
                if ($subscriberUnique && $subscriberUnique->getEntreprise()) {
                    $entreprise->removeSubscriber($subscriber);
                    $return_string = "L'abonné de numéro " . $subscriberUnique->getPhoneNumber() . " appartient déjà à l'entreprise " . $subscriberUnique->getEntreprise()->getName();
                    return new JsonResponse(["success" => false, 'message' => $return_string], Response::HTTP_BAD_REQUEST);
                } elseif ($entreprise->getState() == 1) {
                    if ($subscriber->getSubscription()) {
                        $subscriber->setState(1);
                        $subscriber->setLastSubscriptionDate(new \DateTime('now'));
                        $subscriber->setExpiredState(0);
                    }
                    $subscriber->setEntreprise($entreprise);
                } else {
                    $subscriber->setEntreprise($entreprise);
                }
            }
            $entreprise->setPhoneNumber($entreprise->getAddress()->getPhone());
            if (empty($entreprise->getSubscribers())) {
                return new JsonResponse(["success" => false, 'message' => 'Veuillez ajouter un abonné à cette entreprise'], Response::HTTP_BAD_REQUEST);
            }
            $entreprise = $repositoryEntreprise->saveEntreprise($entreprise);
            $curl_response = null;
            $subscribers_enabled = $repositorySubscriber->findBy(array('entreprise' => $entreprise, 'status' => 1, 'state' => 1));
            foreach ($subscribers_enabled as $subscriber) {
                if ($subscriber->getSubscription()) {
                    $historicalSubscriberSubscription = new HistoricalSubscriberSubscription();
                    $historicalSubscriberSubscription->setSubscriber($subscriber);
                    $historicalSubscriberSubscription->setSubscription($subscriber->getSubscription());
                    $historicalSubscriberSubscription->setSubscriptionDateAndExpirationDate($subscriber->getLastSubscriptionDate());
                    $repositoryHistoricalSubscriberSubscription->saveHistoricalSubscriberSubscription($historicalSubscriberSubscription);
                    if ($subscriber->getEmail()) {
                        $curl_response = json_decode($this->get('curl_service')->createSubscriberAccount($subscriber), true);
                        if ($curl_response['success'] == true) {
                            $this->get('mail_service')->sendMail($curl_response['data']['email'], $curl_response['data']['subject'], $curl_response['data']['message']);
                        }
                    }
                }
            }
            $sendConfirmation = $request->get('send_confirmation');
            if ($sendConfirmation && $sendConfirmation === 'on') {
                $subscribers = $entreprise->getSubscribers();
                foreach ($subscribers as $subscriber) {
                    if ($subscriber->getSubscription() && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                        $this->sendSubscriptionConfirmation($subscriber);
                    }
                }
            }
            $view = View::create(["message" => 'Entreprise ajoutée avec succès', 'curl_response' => $curl_response]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["code" => 200, 'entreprise_content_grid' => $entreprise_content_grid, 'entreprise_content_list' => $entreprise_content_list], Response::HTTP_CREATED);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
            // return new JsonResponse($form, Response::HTTP_BAD_REQUEST);
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
        $serializer = $this->container->get('jms_serializer');
        $repositoryEntreprise = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Entreprise');
        if ($entreprise) {
            $repositoryEntreprise->deleteEntreprise($entreprise);
            $view = View::create(["message" => 'Entreprise supprimée avec succès']);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["message" => 'Entreprise supprimée avec succès'], Response::HTTP_OK);
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
        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');
        $repositorySubDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:SubDomain');
        $repositoryHistoriqueSubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositoryHistoricalSubscriberSubscription = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalSubscriberSubscription');
        $originalSubscribers = new \Doctrine\Common\Collections\ArrayCollection();
        $originalDomains = new \Doctrine\Common\Collections\ArrayCollection();
        $originalSubDomains = new \Doctrine\Common\Collections\ArrayCollection();
        //$this = new TelephoneController();
        $serializer = $this->container->get('jms_serializer');
        if (empty($entreprise)) {
            return new JsonResponse(['message' => 'Entreprise introuvable'], Response::HTTP_NOT_FOUND);
        }
        if ($request->get('action') == 'enable') {
            $entreprise->setState(1);
            $subscribers = $repositorySubscriber->findBy(array('entreprise' => $entreprise, 'status' => 1));
            $curl_response = null;
            foreach ($subscribers as $subscriber) {
                $subscriber->setState(1);
                if ($request->get('subscription_update') != 'others' && $subscriber->getSubscription()) {
                    $subscriber->setLastSubscriptionDate(new \DateTime('now'));
                    $subscriber->setExpiredState(0);
                }
                $subscriber->setEntreprise($entreprise);
            }
            $entreprise = $repositoryEntreprise->updateEntreprise($entreprise);
            foreach ($subscribers as $subscriber) {
                $curl_response = $this->get('curl_service')->enableSubscriberAccount($subscriber, $subscriber->getExpiredState());
            }
            if ($request->get('subscription_update') != 'others') {
                $subscribers_enabled = $repositorySubscriber->findBy(array('entreprise' => $entreprise, 'status' => 1, 'state' => 1));
                foreach ($subscribers_enabled as $subscriber) {
                    if ($subscriber->getSubscription()) {
                        $historicalSubscriberSubscription = new HistoricalSubscriberSubscription();
                        $historicalSubscriberSubscription->setSubscriber($subscriber);
                        $historicalSubscriberSubscription->setSubscription($subscriber->getSubscription());
                        $historicalSubscriberSubscription->setSubscriptionDateAndExpirationDate($subscriber->getLastSubscriptionDate());
                        $repositoryHistoricalSubscriberSubscription->saveHistoricalSubscriberSubscription($historicalSubscriberSubscription);
                    }
                }
            }
            return new JsonResponse(['message' => 'Entreprise activée avec succcès !'], Response::HTTP_OK
            );
        }

        if ($request->get('action') == 'disable') {
            $entreprise->setState(0);
            $subscribers = $repositorySubscriber->findBy(array('entreprise' => $entreprise, 'status' => 1));
            $curl_response = null;
            foreach ($subscribers as $subscriber) {
                $subscriber->setState(0);
                $subscriber->setEntreprise($entreprise);
            }
            $entreprise = $repositoryEntreprise->updateEntreprise($entreprise);
            foreach ($subscribers as $subscriber) {
                $curl_response = $this->get('curl_service')->disableSubscriberAccount($subscriber, $subscriber->getExpiredState());
            }
            return new JsonResponse(['message' => 'Entreprise désactivée avec succcès !'], Response::HTTP_OK
            );
        }
        foreach ($entreprise->getSubscribers() as $subscriber) {
            $originalSubscribers->add($subscriber);
        }
        foreach ($entreprise->getDomains() as $domain) {
            $originalDomains->add($domain);
        }

        foreach ($entreprise->getSubDomains() as $subDomain) {
            $originalSubDomains->add($subDomain);
        }

        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entrepriseUnique = $repositoryEntreprise->findOneBy(array('phoneNumber' => $entreprise->getAddress()->getPhone(), 'status' => 1));
            if ($entrepriseUnique && $entrepriseUnique->getId() != $entreprise->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Une entreprise avec ce numéro de téléphone existe dejà'], Response::HTTP_BAD_REQUEST);
            }


            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $entreprise->setState(1);
                } else {
                    $entreprise->setState(0);
                }
            }


            //*************** gestion des domaines de l'entreprise **************************/

            foreach ($originalDomains as $domain) {
                if (false === $entreprise->getDomains()->contains($domain)) {
                    // remove the entreprise from the subscriber
                    $domain->getEntreprises()->removeElement($entreprise);
                    // if it was a many-to-one relationship, remove the relationship like this

                    $repositoryDomain->updateDomain($domain);
                    // if you wanted to delete the Subscriber entirely, you can also do that
                    // $em->remove($domain);
                }
            }

            $domains = $entreprise->getDomains();
            foreach ($domains as $domain) {
                if (!$originalDomains->contains($domain)) {
                    $domain->addEntreprise($entreprise);
                }
            }

            //*************** gestion des sous-domaines de l'entreprise **************************/

            foreach ($originalSubDomains as $subDomain) {
                if (false === $entreprise->getSubDomains()->contains($subDomain)) {
                    // remove the entreprise from the subscriber
                    $subDomain->getEntreprises()->removeElement($entreprise);
                    // if it was a many-to-one relationship, remove the relationship like this
                    $repositorySubDomain->updateSubDomain($subDomain);
                    // if you wanted to delete the Subscriber entirely, you can also do that
                    // $em->remove($domain);
                }
            }

            $subDomains = $entreprise->getSubDomains();
            foreach ($subDomains as $subDomain) {
                if (!$originalSubDomains->contains($subDomain)) {
                    $subDomain->addEntreprise($entreprise);
                }
            }

            //***************gestion des abonnés de l'entreprise ************************** */
            // remove the relationship between the Entreprise and the Subscribers
            foreach ($originalSubscribers as $subscriber) {
                if (false === $entreprise->getSubscribers()->contains($subscriber)) {
                    // remove the entreprise from the subscriber
                    $entreprise->getSubscribers()->removeElement($subscriber);
                    // if it was a many-to-one relationship, remove the relationship like this
                    $subscriber->setEntreprise(null);
                    $subscriber->setStatus(0);
                    $repositorySubscriber->updateSubscriber($subscriber);
                    // if you wanted to delete the Subscriber entirely, you can also do that
                    // $em->remove($domain);
                }
            }
            $subscribers = $entreprise->getSubscribers();
            foreach ($subscribers as $subscriber) {
                if ($subscriber->getName() == null || $subscriber->getName() == "") {
                    return new JsonResponse(["success" => false, 'message' => "Vous avez des abonnés sans noms. Vueillez les remplir. "], Response::HTTP_BAD_REQUEST);
                }
                if ($subscriber->getPhoneNumber() == null || $subscriber->getPhoneNumber() == "") {
                    return new JsonResponse(["success" => false, 'message' => "Vous avez des abonnés sans numéros de téléphone. Vueillez les remplir. "], Response::HTTP_BAD_REQUEST);
                }

                if ($subscriber->getSubscription() == null) {
                    return new JsonResponse(["success" => false, 'message' => "Vous avez des abonnés sans abonnement. Veuillez leur affecter un abonnement."], Response::HTTP_BAD_REQUEST);
                }

                $subscriberUnique = $repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1));
                if ($subscriberUnique && $subscriberUnique->getId() != $subscriber->getId() && $subscriberUnique->getEntreprise()) {
                    $entreprise->removeSubscriber($subscriber);
                    $return_string = "L'abonné de numéro " . $subscriberUnique->getPhoneNumber() . " appartient déjà à l'entreprise " . $subscriberUnique->getEntreprise()->getName();
                    return new JsonResponse(["success" => false, 'message' => $return_string], Response::HTTP_BAD_REQUEST);
                } else {
                    if ($this->get('security.context')->isGranted('ROLE_ADMIN') && $subscriber->getSubscription()) {
                        if ($sendActivate && $sendActivate === 'on') {
                            $subscriber->setState(1);
                            if ($request->get('subscription_update') != 'others') {
                                $subscriber->setLastSubscriptionDate(new \DateTime('now'));
                            }
                        } else {
                            $subscriber->setState(0);
                        }
                    }

                    $subscriber->setEntreprise($entreprise);
                }
            }
            $entreprise->setPhoneNumber($entreprise->getAddress()->getPhone());
            if (empty($entreprise->getSubscribers())) {
                return new JsonResponse(["success" => false, 'message' => 'Veuillez ajouter un abonné à cette entreprise'], Response::HTTP_BAD_REQUEST);
            }
            $entreprise = $repositoryEntreprise->updateEntreprise($entreprise);
            $curl_response = null;
            $subscribers_enabled = $repositorySubscriber->findBy(array('entreprise' => $entreprise, 'status' => 1, 'state' => 1));
            foreach ($subscribers_enabled as $subscriber) {
                if ($subscriber->getEmail() && $subscriber->getSubscription()) {
                    $curl_response = json_decode($this->get('curl_service')->updateSubscriberAccount($subscriber), true);
//                    if ($curl_response['success'] == true) {
//                        $this->get('mail_service')->sendMail($curl_response['data']['email'], $curl_response['data']['subject'], $curl_response['data']['message']);
//                    }
                }
            }
            if ($request->get('subscription_update') != 'others') {
                foreach ($subscribers_enabled as $subscriber) {
                    if ($subscriber->getSubscription()) {
                        $historicalSubscriberSubscription = new HistoricalSubscriberSubscription();
                        $historicalSubscriberSubscription->setSubscriber($subscriber);
                        $historicalSubscriberSubscription->setSubscription($subscriber->getSubscription());
                        $historicalSubscriberSubscription->setSubscriptionDateAndExpirationDate($subscriber->getLastSubscriptionDate());
                        $repositoryHistoricalSubscriberSubscription->saveHistoricalSubscriberSubscription($historicalSubscriberSubscription);
                    }
                }
            }
            $sendConfirmation = $request->get('send_confirmation');
            if ($sendConfirmation && $sendConfirmation === 'on') {
                foreach ($subscribers_enabled as $subscriber) {
                    if (false === $originalSubscribers->contains($subscriber) && $subscriber->getSubscription()) {
                        $this->sendSubscriptionConfirmation($subscriber);
                    } elseif ($originalSubscribers->contains($subscriber) && $subscriber->getSubscription() && $repositoryHistoriqueSubscriber->findBy(array('subscriber' => $subscriber, 'alertType' => "SMS_CONFIRMATION_SUBSCRIPTION")) === null && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                        $this->sendSubscriptionConfirmation($subscriber);
                    }
                }
            }
//            $entreprise_content_grid = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-grid-edit.html.twig', array('entreprise' => $entreprise));
//            $entreprise_content_list = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-list-edit.html.twig', array('entreprise' => $entreprise));
            //$view = View::create(["code" => 200, 'entreprise_content_grid' => $entreprise_content_grid, 'entreprise_content_list' => $entreprise_content_list]);
            $view = View::create(["message" => 'Entreprise modifiée avec succès', "curl_response" => $curl_response]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["code" => 200,'entreprise_content_grid' => $entreprise_content_grid, 'entreprise_content_list' => $entreprise_content_list], Response::HTTP_OK);
        } else {
            $edit_entreprise_form = $this->renderView('OGIVEAlertBundle:entreprise:edit.html.twig', array('form' => $form->createView(), 'entreprise' => $entreprise));
            $view = View::create(['edit_entreprise_form' => $edit_entreprise_form]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["code" => 200, 'edit_entreprise_form' => $edit_entreprise_form], Response::HTTP_OK);
        }
    }

    public function sendSubscriptionConfirmation(\OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $cout = "";
        if ($subscriber->getSubscription()->getPeriodicity() === 1) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 1 an";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 2) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 6 mois";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 3) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 3 mois";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 4) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 1 mois";
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 5) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 1 semaine";
        }
        $content = $subscriber->getEntreprise()->getName() . ", votre souscription au service <<Appels d'offres Infos>> a été éffectuée avec succès. \nCoût du forfait = " . $cout . ". \nOGIVE SOLUTIONS vous remercie pour votre confiance.";
        $this->get('sms_service')->sendSms($subscriber->getPhoneNumber(), $content);
        $this->get('mail_service')->sendMail($subscriber->getEmail(), "CONFIRMATION DE L'ABONNEMENT", $content);
        $historiqueAlertSubscriber->setMessage($content);
        $historiqueAlertSubscriber->setSubscriber($subscriber);
        $historiqueAlertSubscriber->setAlertType("SMS_CONFIRMATION_SUBSCRIPTION");
        return $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
    }

}
