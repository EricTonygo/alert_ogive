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
        //$this = new TelephoneController();
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $form = $this->createForm('OGIVE\AlertBundle\Form\SubscriberType', $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'email' => $subscriber->getEmail(), 'status' => 1))) {
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

            $subscriber = $repositorySubscriber->saveSubscriber($subscriber);
            $sendConfirmation = $request->get('send_confirmation');
            if ($sendConfirmation && $sendConfirmation === 'on') {
                if ($subscriber->getSubscription() && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $this->sendSubscriptionConfirmation($subscriber);
                }
            }
//            $subscriber_content_grid = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-grid.html.twig', array('subscriber' => $subscriber));
//            $subscriber_content_list = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-list.html.twig', array('subscriber' => $subscriber));
//            $view = View::create(["code" => 200, 'subscriber_content_grid' => $subscriber_content_grid, 'subscriber_content_list' => $subscriber_content_list]);
            $view = View::create(["message" => 'Abonné ajouté avec succès']);
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

        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $repositoryHistoriqueSubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $oldSubscription = $subscriber->getSubscription();
        //$this = new TelephoneController();
        if (empty($subscriber)) {
            return new JsonResponse(['message' => 'Abonné introuvable'], Response::HTTP_NOT_FOUND);
        }

        if ($request->get('action') == 'enable') {
            if ($subscriber->getSubscription() && $subscriber->getEntreprise()->getState() == 1) {
                $subscriber->setState(1);
                $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
                return new JsonResponse(['message' => 'Abonné activé avec succcès !'], Response::HTTP_OK
                );
            } else {
                return new JsonResponse(['message' => "Impossible d'activer cet abonné"], Response::HTTP_NOT_FOUND);
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
            $subscriberUnique = $repositorySubscriber->findOneBy(array('phoneNumber' => $subscriber->getPhoneNumber(), 'email' => $subscriber->getEmail(), 'status' => 1));
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

            $subscriber = $repositorySubscriber->updateSubscriber($subscriber);
            $sendConfirmation = $request->get('send_confirmation');
            if ($sendConfirmation && $sendConfirmation === 'on') {
                if ($oldSubscription === null && $subscriber->getSubscription() && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $this->sendSubscriptionConfirmation($subscriber);
                } elseif ($oldSubscription && $subscriber->getSubscription() && $oldSubscription->getId() != $subscriber->getSubscription()->getId() && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $this->sendSubscriptionConfirmation($subscriber);
                } elseif ($subscriber->getSubscription() && $repositoryHistoriqueSubscriber->findBy(array('subscriber' => $subscriber, 'alertType' => "SMS_CONFIRMATION_SUBSCRIPTION", "status" => 1)) == null && $subscriber->getStatus() == 1 && $subscriber->getState() == 1) {
                    $this->sendSubscriptionConfirmation($subscriber);
                }
            }

//            $subscriber_content_grid = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-grid-edit.html.twig', array('subscriber' => $subscriber));
//            $subscriber_content_list = $this->renderView('OGIVEAlertBundle:subscriber:subscriber-list-edit.html.twig', array('subscriber' => $subscriber));
//            $view = View::create(["code" => 200, 'subscriber_content_grid' => $subscriber_content_grid, 'subscriber_content_list' => $subscriber_content_list]);
            $view = View::create(["message" => 'Abonné modifié avec succès']);
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

    public function sendSubscriptionConfirmation(Subscriber $subscriber) {
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
        } elseif ($subscriber->getSubscription()->getPeriodicity() === 4) {
            $cout = $subscriber->getSubscription()->getPrice() . " " . $subscriber->getSubscription()->getCurrency() . ", validité = 1 semaine";
        }
        $content = $subscriber->getEntreprise()->getName() . ", votre souscription au service <<Appels d'offres Infos>> a été éffectuée avec succès. \nCoût du forfait = " . $cout . ". \nOGIVE SOLUTIONS vous remercie pour votre confiance.";
        $twilio = $this->get('twilio.api');
        //$messages = $twilio->account->messages->read();
        $message = $twilio->account->messages->sendMessage(
                'OGIVE INFOS', // From a Twilio number in your account
                $subscriber->getPhoneNumber(), // Text any number
                $content
        );
        $this->sendEmailSubscriber($subscriber, "CONFIRMATION DE L'ABONNEMENT", $content);
        $historiqueAlertSubscriber->setMessage($content);
        $historiqueAlertSubscriber->setSubscriber($subscriber);
        $historiqueAlertSubscriber->setAlertType("SMS_CONFIRMATION_SUBSCRIPTION");

        return $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
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
            return new JsonResponse(['message' => "Error lors de l'envoi du message"], Response::HTTP_NOT_FOUND);
        }
        $view = View::create(['message' => "Accusé de reception envoyé avec succès"]);
        $view->setFormat('json');
        return $view;
    }

    public function sendEmailSubscriber(Subscriber $subscriber, $subject, $content, \OGIVE\AlertBundle\Entity\AlertProcedure $procedure = null) {
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array('infos@si-ogive.com' => "OGIVE INFOS"))
                ->setTo($subscriber->getEmail())
                ->setBody(
                $content
        );
        if ($procedure) {
            $piecesjointes = $procedure->getPiecesjointes();
            $originalpiecesjointes = $procedure->getOriginalpiecesjointes();
            if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
                for ($i = 0; $i < count($piecesjointes); $i++) {
                    $attachment = \Swift_Attachment::fromPath($procedure->getUploadRootDir() . '/' . $piecesjointes[$i])
                            ->setFilename($originalpiecesjointes[$i]);
                    $message->attach($attachment);
                }
            }
        }

        $this->get('mailer')->send($message);
    }

}
