<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber;
use OGIVE\AlertBundle\Entity\Subscriber;
use OGIVE\AlertBundle\Entity\CallOffer;
use OGIVE\AlertBundle\Entity\ProcedureResult;
use OGIVE\AlertBundle\Entity\Additive;
use OGIVE\AlertBundle\Entity\SpecialFollowUp;
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
use Twilio\Rest\Client;

class TelephoneController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Post("/send-sms-subscriber/{id}" , name="send_sms_subscriber", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendSmsSubscriberAction(Request $request, Subscriber $subscriber) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $form = $this->createForm('OGIVE\AlertBundle\Form\HistoricalAlertSubscriberType', $historiqueAlertSubscriber);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            $twilio = $this->get('twilio.client');
//            $message = $twilio->messages->create(
//                    $subscriber->getPhoneNumber(), // Text any number
//                    array(
//                'from' => 'OGIVE INFOS', // From a Twilio number in your account
//                'body' => $historiqueAlertSubscriber->getMessage()
//                    )
//            );
            $whatsappResponse = $this->sendMessageViaWhatsapp($subscriber->getPhoneNumber(), $historiqueAlertSubscriber->getMessage());
            $view = View::create(['message' => "Message envoyé avec succès", "whatsappResponse" => $whatsappResponse]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $send_sms_subscriber_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_sms.html.twig', array(
                'subscriber' => $subscriber,
                'form' => $form->createView()
            ));
            $view = View::create(['send_sms_subscriber_form' => $send_sms_subscriber_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    ////////////////// Send SMS Call Offer ///////////////////////////////////

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-call-offer/{id}" , name="send_notification_callOffer_post", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function postSendNotificationCallOfferAction(Request $request, CallOffer $callOffer) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');

        $twilio = $this->get('twilio.client');
        $sendToAll = $request->get('all_subscribers');
        $idSubscribers = $request->get('subscribers');
        if ($sendToAll && $sendToAll === 'on') {
            if ($callOffer->getSubDomain()) {
                $entreprises = $callOffer->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } elseif ($callOffer->getDomain()) {
                $entreprises = $callOffer->getDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } else {
                $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
            }
            $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($entreprises as $entreprise) {
                $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                    return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
                });
                $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                    if (!$subscribers->contains($subscriber)) {
                        $subscribers->add($subscriber);
                    }
                });
            }
            foreach ($subscribers as $subscriber) {

                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $callOffer);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
//            $callOffer->setAbstract($request->get('abstract'));
//            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } elseif ($idSubscribers && is_array($idSubscribers) && !empty($idSubscribers)) {
            foreach ($idSubscribers as $idSubscriber) {
                $idSubscriber = (int) $idSubscriber;
                $subscriber = $repositorySubscriber->find($idSubscriber);

                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $callOffer);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
//            $callOffer->setAbstract($request->get('abstract'));
//            $callOffer = $repositoryCallOffer->updateCallOffer($callOffer);
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["success" => false, 'message' => "Echec de l'envoi des messages"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/get-all-account-message" , name="get_all_message_account", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getAllMessagesAction(Request $request) {
        $twilio = $this->get('twilio.client');
        $messages = $twilio->messages->read();
        $view = View::create(['messages' => $messages, "message" => 'Domaine Ajouté avec succès']);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/send-notification-call-offer/{id}" , name="send_notification_callOffer_get", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationCallOfferAction(Request $request, CallOffer $callOffer) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if ($callOffer->getSubDomain()) {
            $entreprises = $callOffer->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } elseif ($callOffer->getDomain()) {
            $entreprises = $callOffer->getDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } else {
            $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($entreprises as $entreprise) {
            $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
            });
            $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                if (!$subscribers->contains($subscriber)) {
                    $subscribers->add($subscriber);
                }
            });
        }
        $send_notification_callOffer_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_callOffer.html.twig', array(
            'subscribers' => $subscribers,
            'callOffer' => $callOffer,
        ));
        $view = View::create(['send_notification_callOffer_form' => $send_notification_callOffer_form]);
        $view->setFormat('json');
        return $view;
    }

    ////////////////////send SMS Procedure result ///////////////////////////////////

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-result/{id}" , name="send_notification_procedureResult_post", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function postSendNotificationProcedureResultAction(Request $request, ProcedureResult $procedureResult) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');

        $twilio = $this->get('twilio.client');
        $sendToAll = $request->get('all_subscribers');
        $idSubscribers = $request->get('subscribers');
        if ($sendToAll && $sendToAll === 'on') {
            if ($procedureResult->getSubDomain()) {
                $entreprises = $procedureResult->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } elseif ($procedureResult->getDomain()) {
                $entreprises = $procedureResult->getDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } else {
                $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
            }
            $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($entreprises as $entreprise) {
                $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                    return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
                });
                $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                    if (!$subscribers->contains($subscriber)) {
                        $subscribers->add($subscriber);
                    }
                });
            }
            foreach ($subscribers as $subscriber) {
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $procedureResult);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } elseif ($idSubscribers && is_array($idSubscribers) && !empty($idSubscribers)) {
            foreach ($idSubscribers as $idSubscriber) {
                $subscriber = $repositorySubscriber->find((int) $idSubscriber);
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $procedureResult);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
//            $procedureResult->setAbstract($request->get('abstract'));
//            $procedureResult = $repositoryProcedureResult->updateProcedureResult($procedureResult);
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["success" => false, 'message' => "Echec de l'envoi des messages"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/send-notification-result/{id}" , name="send_notification_procedureResult_get", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationProcedureResultAction(Request $request, ProcedureResult $procedureResult) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if ($procedureResult->getSubDomain()) {
            $entreprises = $procedureResult->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } elseif ($procedureResult->getDomain()) {
            $entreprises = $procedureResult->getDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } else {
            $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($entreprises as $entreprise) {
            $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
            });
            $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                if (!$subscribers->contains($subscriber)) {
                    $subscribers->add($subscriber);
                }
            });
        }
        $send_notification_procedureResult_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_procedureResult.html.twig', array(
            'subscribers' => $subscribers,
            'procedureResult' => $procedureResult,
        ));
        $view = View::create(['send_notification_procedureResult_form' => $send_notification_procedureResult_form]);
        $view->setFormat('json');
        return $view;
    }

////////////////////send SMS Additive ///////////////////////////////////

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-additive/{id}" , name="send_notification_additive_post", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function postSendNotificationAdditiveAction(Request $request, Additive $additive) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');

        $twilio = $this->get('twilio.client');
        $sendToAll = $request->get('all_subscribers');
        $idSubscribers = $request->get('subscribers');
        if ($sendToAll && $sendToAll === 'on') {
            if ($additive->getSubDomain()) {
                $entreprises = $additive->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } elseif ($additive->getDomain()) {
                $entreprises = $additive->getDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } else {
                $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
            }
            $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($entreprises as $entreprise) {
                $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                    return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
                });
                $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                    if (!$subscribers->contains($subscriber)) {
                        $subscribers->add($subscriber);
                    }
                });
            }
            foreach ($subscribers as $subscriber) {
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $additive);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
//            $additive->setAbstract($request->get('abstract'));
//            $additive = $repositoryAdditive->updateAdditive($additive);
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } elseif ($idSubscribers && is_array($idSubscribers) && !empty($idSubscribers)) {
            foreach ($idSubscribers as $idSubscriber) {
                $subscriber = $repositorySubscriber->find((int) $idSubscriber);
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $additive);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
//            $additive->setAbstract($request->get('abstract'));
//            $additive = $repositoryAdditive->updateAdditive($additive);
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["success" => false, 'message' => "Echec de l'envoi des messages"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/send-notification-additive/{id}" , name="send_notification_additive_get", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationAdditiveAction(Request $request, Additive $additive) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if ($additive->getSubDomain()) {
            $entreprises = $additive->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } elseif ($additive->getDomain()) {
            $entreprises = $additive->getDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } else {
            $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($entreprises as $entreprise) {
            $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
            });
            $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                if (!$subscribers->contains($subscriber)) {
                    $subscribers->add($subscriber);
                }
            });
        }
        $send_notification_additive_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_additive.html.twig', array(
            'subscribers' => $subscribers,
            'additive' => $additive,
        ));
        $view = View::create(['send_notification_additive_form' => $send_notification_additive_form]);
        $view->setFormat('json');
        return $view;
    }

    ////////////////////send SMS Expression interest ///////////////////////////////////

    /**
     * @Rest\View()
     * @Rest\Post("/send-notification-expression-interest/{id}" , name="send_notification_expressionInterest_post", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function postSendNotificationExpressionInterestAction(Request $request, ExpressionInterest $expressionInterest) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');

        $twilio = $this->get('twilio.client');
        $sendToAll = $request->get('all_subscribers');
        $idSubscribers = $request->get('subscribers');
        if ($sendToAll && $sendToAll === 'on') {
            if ($expressionInterest->getSubDomain()) {
                $entreprises = $expressionInterest->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } elseif ($expressionInterest->getDomain()) {
                $entreprises = $expressionInterest->getDomain()->getEntreprises()->filter(function ($entreprise) {
                    return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
                });
            } else {
                $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
            }
            $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($entreprises as $entreprise) {
                $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                    return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
                });
                $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                    if (!$subscribers->contains($subscriber)) {
                        $subscribers->add($subscriber);
                    }
                });
            }
            foreach ($subscribers as $subscriber) {
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $expressionInterest);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
//            $expressionInterest->setAbstract($request->get('abstract'));
//            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } elseif ($idSubscribers && is_array($idSubscribers) && !empty($idSubscribers)) {
            foreach ($idSubscribers as $idSubscriber) {
                $subscriber = $repositorySubscriber->find((int) $idSubscriber);
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, "APPELS D'OFFRES INFOS", $request->get('abstract'), $expressionInterest);
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
//            $expressionInterest->setAbstract($request->get('abstract'));
//            $expressionInterest = $repositoryExpressionInterest->updateExpressionInterest($expressionInterest);
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["success" => false, 'message' => "Echec de l'envoi des messages"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/send-notification-expression-interest/{id}" , name="send_notification_expressionInterest_get", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendNotificationExpressionInterestAction(Request $request, ExpressionInterest $expressionInterest) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if ($expressionInterest->getSubDomain()) {
            $entreprises = $expressionInterest->getSubDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } elseif ($expressionInterest->getDomain()) {
            $entreprises = $expressionInterest->getDomain()->getEntreprises()->filter(function ($entreprise) {
                return $entreprise->getStatus() == 1 && $entreprise->getState() == 1;
            });
        } else {
            $entreprises = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        $entreprise_subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($entreprises as $entreprise) {
            $entreprise_subscribers = $entreprise->getSubscribers()->filter(function ($subcriber_entreprise) {
                return $subcriber_entreprise->getStatus() == 1 && $subcriber_entreprise->getState() == 1;
            });
            $entreprise_subscribers->map(function ($subscriber) use (&$subscribers) {
                if (!$subscribers->contains($subscriber)) {
                    $subscribers->add($subscriber);
                }
            });
        }
        $send_notification_expressionInterest_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_notification_expressionInterest.html.twig', array(
            'subscribers' => $subscribers,
            'expressionInterest' => $expressionInterest,
        ));
        $view = View::create(['send_notification_expressionInterest_form' => $send_notification_expressionInterest_form]);
        $view->setFormat('json');
        return $view;
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
        $twilio = $this->get('twilio.client');

        $message = $twilio->messages->create(
                $subscriber->getPhoneNumber(), // Text any number
                array(
            'from' => 'OGIVE INFOS', // From a Twilio number in your account
            'body' => $content
                )
        );
        $this->sendEmailSubscriber($subscriber, "CONFIRMATION DE L'ABONNEMENT", $content);
        $historiqueAlertSubscriber->setMessage($content);
        $historiqueAlertSubscriber->setSubscriber($subscriber);
        $historiqueAlertSubscriber->setAlertType("SMS_CONFIRMATION_SUBSCRIPTION");

        return $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
    }

    ////////////////////send SMS Spécial Follow Up ///////////////////////////////////

    /**
     * @Rest\View()
     * @Rest\Post("/send-special-follow-up/{id}" , name="send_special_follow_up_post", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function postSendSpecialFollowUpAction(Request $request, SpecialFollowUp $specialFollowUp) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $historiqueAlertSubscriber = new HistoricalAlertSubscriber();
        $repositoryHistorique = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:HistoricalAlertSubscriber');
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');

        $twilio = $this->get('twilio.client');
        $sendToAll = $request->get('all_subscribers');
        $idSubscribers = $request->get('subscribers');
        if ($sendToAll && $sendToAll === 'on') {
            $subscribers = $repositorySubscriber->getAll();
            foreach ($subscribers as $subscriber) {
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, $specialFollowUp->getName(), $request->get('abstract'));
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } elseif ($idSubscribers && is_array($idSubscribers) && !empty($idSubscribers)) {
            foreach ($idSubscribers as $idSubscriber) {
                $subscriber = $repositorySubscriber->find((int) $idSubscriber);
                $message = $twilio->messages->create(
                        $subscriber->getPhoneNumber(), // Text any number
                        array(
                    'from' => 'OGIVE INFOS', // From a Twilio number in your account
                    'body' => $request->get('abstract')
                        )
                );
                $this->sendEmailSubscriber($subscriber, $specialFollowUp->getName(), $request->get('abstract'));
                $historiqueAlertSubscriber->setMessage($request->get('abstract'));
                $historiqueAlertSubscriber->setSubscriber($subscriber);
                $historiqueAlertSubscriber->setAlertType("EMAIL");
                $historiqueAlertSubscriber = $repositoryHistorique->saveHistoricalAlertSubscriber($historiqueAlertSubscriber);
            }
            $view = View::create(['message' => "SMS et Email envoyés avec succès"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["success" => false, 'message' => "Echec de l'envoi des messages"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/send-special-follow-up/{id}" , name="send_special_follow_up_get", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getSendSpecialFollowUpAction(Request $request, SpecialFollowUp $specialFollowUp) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositorySubscriber = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Subscriber');
        $subscribers = $repositorySubscriber->getAll();
        $send_special_follow_up_form = $this->renderView('OGIVEAlertBundle:send_sms:form_send_special_follow_up.html.twig', array(
            'subscribers' => $subscribers,
            'specialFollowUp' => $specialFollowUp,
        ));
        $view = View::create(['send_special_follow_up_form' => $send_special_follow_up_form]);
        $view->setFormat('json');
        return $view;
    }

    public function sendEmailSubscriber(Subscriber $subscriber, $subject, $content, \OGIVE\AlertBundle\Entity\AlertProcedure $procedure = null) {
        if ($subscriber && $subscriber->getEmail() != "") {
            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array('infos@siogive.com' => "OGIVE INFOS"))
                    ->setTo($subscriber->getEmail())
                    ->setBody(
                    $content
            );
            if ($procedure) {
                $piecesjointes = $procedure->getPiecesjointes();
                $originalpiecesjointes = $procedure->getOriginalpiecesjointes();
                if (!empty($piecesjointes) && !empty($originalpiecesjointes) && count($piecesjointes) == count($originalpiecesjointes)) {
                    for ($i = 0; $i < count($piecesjointes); $i++) {
                        if (file_exists($procedure->getUploadRootDir() . '/' . $piecesjointes[$i])) {
                            $attachment = \Swift_Attachment::fromPath($procedure->getUploadRootDir() . '/' . $piecesjointes[$i])
                                    ->setFilename($originalpiecesjointes[$i]);
                            $message->attach($attachment);
                        }
                    }
                }
            }
            $this->get('mailer')->send($message);
        } else {
            return true;
        }
    }

    function sendMessageViaWhatsapp($number, $message) {
        $INSTANCE_ID = 'YOUR_INSTANCE_ID_HERE';  // TODO: Replace it with your gateway instance ID here
        $CLIENT_ID = "YOUR_CLIENT_ID_HERE";  // TODO: Replace it with your Forever Green client ID here
        $CLIENT_SECRET = "YOUR_CLIENT_SECRET_HERE";   // TODO: Replace it with your Forever Green client secret here
        $postData = array(
            'number' => str_replace('+','', $number), // TODO: Specify the recipient's number here. NOT the gateway number
            'message' => $message
        );
        $headers = array(
            'Content-Type: application/json',
            'X-WM-CLIENT-ID: ' . $CLIENT_ID,
            'X-WM-CLIENT-SECRET: ' . $CLIENT_SECRET
        );
        $url = 'http://api.whatsmate.net/v2/whatsapp/single/message/' . $INSTANCE_ID;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}
