<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Entreprise;
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
        //$this = new TelephoneController();
        $repositoryEntreprise = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Entreprise');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $form = $this->createForm('OGIVE\AlertBundle\Form\EntrepriseType', $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositoryEntreprise->findOneBy(array('name' => $entreprise->getName(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Une entreprise avec ce nom existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
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

                if ($repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1)) !== null) {
                    $entreprise->removeSubscriber($subscriber);
                } elseif ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    if ($subscriber->getSubscription()) {
                        $subscriber->setState(1);
                    }
                    $subscriber->setEntreprise($entreprise);
                } else {
                    $subscriber->setEntreprise($entreprise);
                }
            }
            $entreprise = $repositoryEntreprise->saveEntreprise($entreprise);
            $sendConfirmation = $request->get('send_confirmation');
            if ($sendConfirmation && $sendConfirmation === 'on') {
                $subscribers = $entreprise->getSubscribers();
                foreach ($subscribers as $subscriber) {
                    if ($subscriber->getSubscription() && $subscriber->getStatus()==1 && $subscriber->getState()==1) {
                        $this->sendSubscriptionConfirmation($subscriber);
                    }
                }
            }
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
        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');
        $repositorySubDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:SubDomain');
        $repositoryHistoriqueSubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
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
            $subscribers = $entreprise->getSubscribers();
            foreach ($subscribers as $subscriber) {
                $subscriber->setState(1);
                $subscriber->setEntreprise($entreprise);
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
            $entrepriseUnique = $repositoryEntreprise->findOneBy(array('name' => $entreprise->getName(), 'status' => 1));
            if ($entrepriseUnique && $entrepriseUnique->getId() != $entreprise->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Une entreprise avec ce nom existe dejà'], Response::HTTP_NOT_FOUND);
            }

            //*************** gestion des domaines de l'entreprise **************************/

            foreach ($originalDomains as $domain) {
                if (false === $entreprise->getDomains()->contains($domain)) {
                    // remove the entreprise from the subscriber
                    $entreprise->getDomains()->removeElement($domain);
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
                    $entreprise->getSubDomains()->removeElement($subDomain);
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
                $subscriberUnique = $repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'status' => 1));
                if ($subscriberUnique && $subscriberUnique->getId() != $subscriber->getId()) {
                    $entreprise->removeSubscriber($subscriber);
                } else {
                    if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                        $entreprise->setState(1);
                    }
                    $subscriber->setEntreprise($entreprise);
                }
            }
            $entreprise = $repositoryEntreprise->updateEntreprise($entreprise);
            $sendConfirmation = $request->get('send_confirmation');
            if ($sendConfirmation && $sendConfirmation === 'on') {
                $subscribers = $entreprise->getSubscribers();
                foreach ($subscribers as $subscriber) {
                    if (false === $originalSubscribers->contains($subscriber) && $subscriber->getSubscription() && $subscriber->getStatus()==1 && $subscriber->getState()==1) {
                        $this->sendSubscriptionConfirmation($subscriber);
                    } elseif ($originalSubscribers->contains($subscriber) && $subscriber->getSubscription() && $repositoryHistoriqueSubscriber->findBy(array('subscriber' => $subscriber, 'alertType' => "SMS_CONFIRMATION_SUBSCRIPTION")) === null && $subscriber->getStatus()==1 && $subscriber->getState()==1) {
                        $this->sendSubscriptionConfirmation($subscriber);
                    }
                }
            }
            //$entreprise->upload();
            $entreprise_content_grid = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-grid-edit.html.twig', array('entreprise' => $entreprise));
            $entreprise_content_list = $this->renderView('OGIVEAlertBundle:entreprise:entreprise-list-edit.html.twig', array('entreprise' => $entreprise));

            $entreprise_json = $serializer->serialize($entreprise, 'json');
            $view = View::create(["code" => 200, 'entreprise' => $entreprise_json, 'entreprise_content_grid' => $entreprise_content_grid, 'entreprise_content_list' => $entreprise_content_list]);
            $view->setFormat('json');
            return $view;
        } else {
            $entreprise_json = $serializer->serialize($entreprise, 'json');
            $edit_entreprise_form = $this->renderView('OGIVEAlertBundle:entreprise:edit.html.twig', array('form' => $form->createView(), 'entreprise' => $entreprise));
            $view = View::create(["code" => 200, 'entreprise' => $entreprise, 'edit_entreprise_form' => $edit_entreprise_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    public function sendSubscriptionConfirmation(\OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $cout = "";
        if ($subscriber->getSubscription()) {
            if ($subscriber->getSubscription()->getPeriodicity() === 1) {
                $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . " / an";
            } elseif ($subscriber->getSubscription()->getPeriodicity() === 2) {
                $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . " / mois";
            } elseif ($subscriber->getSubscription()->getPeriodicity() === 1) {
                $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . " / semaine";
            }
        }

        $content = "M/Mr. " . $subscriber->getName() . " Votre souscription au service l'alerte de messagerie pour les marchés publics a été éffectuée avec succès. \nCoût du forfait = " . $cout . ". \nOGIVE SOLUTIONS vous remercie pour votre confiance.";
        $twilio = $this->get('twilio.api');
        //$messages = $twilio->account->messages->read();
        $message = $twilio->account->messages->sendMessage(
                'MG8e369c4e5ea49ce989834c5355a1f02f', // From a Twilio number in your account
                $subscriber->getPhoneNumber(), // Text any number
                $content
        );
        $historiqueAlertSubscriber->setMessage($content);
        $historiqueAlertSubscriber->setSubscriber($subscriber);
        $historiqueAlertSubscriber->setAlertType("SMS_CONFIRMATION_SUBSCRIPTION");

        return $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
    }

}
