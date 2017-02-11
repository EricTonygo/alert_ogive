<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\ProcedureResult;
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
 * ProcedureResult controller.
 *
 */
class ProcedureResultController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/attributions" , name="procedureResult_index", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function getProcedureResultsAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->get('security.authorization_checker')->isGranted('FACTURIER')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $em = $this->getDoctrine()->getManager();
        $procedureResult = new ProcedureResult();
        $form = $this->createForm('OGIVE\AlertBundle\Form\ProcedureResultType', $procedureResult);
        $procedureResults = $em->getRepository('OGIVEAlertBundle:ProcedureResult')->getAll();
        return $this->render('OGIVEAlertBundle:procedureResult:index.html.twig', array(
                    'procedureResults' => $procedureResults,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/attributions/{id}" , name="procedureResult_get_one", options={ "method_prefix" = false, "expose" = true })
     */
    public function getProcedureResultByIdAction(ProcedureResult $procedureResult) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->get('security.authorization_checker')->isGranted('FACTURIER')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        if (empty($procedureResult)) {
            return new JsonResponse(['message' => "Attribution introuvable"], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\ProcedureResultType', $procedureResult, array('method' => 'PUT'));
        $procedureResult_details = $this->renderView('OGIVEAlertBundle:procedureResult:show.html.twig', array(
            'procedureResult' => $procedureResult,
            'form' => $form->createView()
        ));
        $view = View::create(['procedureResult_details' => $procedureResult_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/attributions", name="procedureResult_add", options={ "method_prefix" = false, "expose" = true })
     */
    public function postProcedureResultsAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->get('security.authorization_checker')->isGranted('FACTURIER')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $procedureResult = new ProcedureResult();
        $repositoryProcedureResult = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:ProcedureResult');
        $form = $this->createForm('OGIVE\AlertBundle\Form\ProcedureResultType', $procedureResult);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositoryProcedureResult->findOneBy(array('reference' => $procedureResult->getReference(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => "Une attribution avec ce numéro existe dejà !"], Response::HTTP_BAD_REQUEST);
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $procedureResult->setState(1);
                }
            }
            $procedureResult->setType($request->get('attribution_type'));
            $procedureResult->setAbstract($this->getAbstractOfProcedureResult($procedureResult));
            if ($procedureResult->getCallOffer()) {
                $procedureResult->setDomain($procedureResult->getCallOffer()->getDomain());
                $procedureResult->setSubDomain($procedureResult->getCallOffer()->getSubDomain());
                $procedureResult->setOwner($procedureResult->getCallOffer()->getOwner());
                $procedureResult->setObject($procedureResult->getCallOffer()->getObject());
            } elseif ($procedureResult->getExpressionInterest()) {
                $procedureResult->setDomain($procedureResult->getExpressionInterest()->getDomain());
                $procedureResult->setSubDomain($procedureResult->getExpressionInterest()->getSubDomain());
                $procedureResult->setOwner($procedureResult->getExpressionInterest()->getOwner());
                $procedureResult->setObject($procedureResult->getExpressionInterest()->getObject());
            }
            $procedureResult = $repositoryProcedureResult->saveProcedureResult($procedureResult);
//            $procedureResult_content_grid = $this->renderView('OGIVEAlertBundle:procedureResult:procedureResult-grid.html.twig', array('procedureResult' => $procedureResult));
//            $procedureResult_content_list = $this->renderView('OGIVEAlertBundle:procedureResult:procedureResult-list.html.twig', array('procedureResult' => $procedureResult));
//            $view = View::create(["code" => 200, 'procedureResult_content_grid' => $procedureResult_content_grid, 'procedureResult_content_list' => $procedureResult_content_list]);
            $view = View::create(["message" => "Attribution ajoutée avec succès !"]);
            $view->setFormat('json');
            return $view;
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/attributions/{id}", name="procedureResult_delete", options={ "method_prefix" = false, "expose" = true })
     */
    public function removeProcedureResultAction(ProcedureResult $procedureResult) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->get('security.authorization_checker')->isGranted('FACTURIER')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        $repositoryProcedureResult = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:ProcedureResult');
        if ($procedureResult) {
            $repositoryProcedureResult->deleteProcedureResult($procedureResult);
            $view = View::create(["message" => "Attribution supprimée avec succès !"]);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => "Attribution introuvable"], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/attributions/{id}", name="procedureResult_update", options={ "method_prefix" = false, "expose" = true })
     * @param Request $request
     */
    public function putProcedureResultAction(Request $request, ProcedureResult $procedureResult) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED') || $this->get('security.authorization_checker')->isGranted('FACTURIER')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        return $this->updateProcedureResultAction($request, $procedureResult);
    }

    public function updateProcedureResultAction(Request $request, ProcedureResult $procedureResult) {

        $repositoryProcedureResult = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:ProcedureResult');

        if (empty($procedureResult)) {
            return new JsonResponse(['message' => "Attribution introuvable"], Response::HTTP_NOT_FOUND);
        }

        if ($request->get('action') == 'enable') {
            $procedureResult->setState(1);
            $procedureResult = $repositoryProcedureResult->updateProcedureResult($procedureResult);
            return new JsonResponse(['message' => "Attribution activée avec succcès !"], Response::HTTP_OK
            );
        }

        if ($request->get('action') == 'disable') {
            $procedureResult->setState(0);
            $procedureResult = $repositoryProcedureResult->updateProcedureResult($procedureResult);
            return new JsonResponse(['message' => "Attribution désactivée avec succcès !"], Response::HTTP_OK
            );
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\ProcedureResultType', $procedureResult, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $procedureResultUnique = $repositoryProcedureResult->findOneBy(array('reference' => $procedureResult->getReference(), 'status' => 1));
            if ($procedureResultUnique && $procedureResultUnique->getId() != $procedureResult->getId()) {
                return new JsonResponse(["success" => false, 'message' => "Une attribution avec ce numéro existe dejà"], Response::HTTP_BAD_REQUEST);
            }
            $procedureResult->setAbstract($this->getAbstractOfProcedureResult($procedureResult));
            $procedureResult->setType($request->get('attribution_type'));
            if ($procedureResult->getCallOffer()) {
                $procedureResult->setDomain($procedureResult->getCallOffer()->getDomain());
                $procedureResult->setSubDomain($procedureResult->getCallOffer()->getSubDomain());
                $procedureResult->setOwner($procedureResult->getCallOffer()->getOwner());
                //$procedureResult->setObject($procedureResult->getCallOffer()->getObject());
            } elseif ($procedureResult->getExpressionInterest()) {
                $procedureResult->setDomain($procedureResult->getExpressionInterest()->getDomain());
                $procedureResult->setSubDomain($procedureResult->getExpressionInterest()->getSubDomain());
                $procedureResult->setOwner($procedureResult->getExpressionInterest()->getOwner());
                //$procedureResult->setObject($procedureResult->getExpressionInterest()->getObject());
            }
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $sendActivate = $request->get('send_activate');
                if ($sendActivate && $sendActivate === 'on') {
                    $procedureResult->setState(1);
                } else {
                    $procedureResult->setState(0);
                }
            }
            $procedureResult = $repositoryProcedureResult->updateProcedureResult($procedureResult);
//            $procedureResult_content_grid = $this->renderView('OGIVEAlertBundle:procedureResult:procedureResult-grid-edit.html.twig', array('procedureResult' => $procedureResult));
//            $procedureResult_content_list = $this->renderView('OGIVEAlertBundle:procedureResult:procedureResult-list-edit.html.twig', array('procedureResult' => $procedureResult));
//            $view = View::create(["code" => 200, 'procedureResult_content_grid' => $procedureResult_content_grid, 'procedureResult_content_list' => $procedureResult_content_list]);
            $view = View::create(["message" => "Attribution modifiée avec succès !"]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_procedureResult_form = $this->renderView('OGIVEAlertBundle:procedureResult:edit.html.twig', array('form' => $form->createView(), 'procedureResult' => $procedureResult));
            $view = View::create(['edit_procedureResult_form' => $edit_procedureResult_form]);
            $view->setFormat('json');
            return $view;
        }
    }

    public function getAbstractOfProcedureResult(ProcedureResult $procedureResult) {
        $contact = "Contacts: 694200310 - 694202013";
        if ($procedureResult && $procedureResult->getCallOffer()) {
            return "Décision " . "N°".$procedureResult->getReference(). "/D/" . $procedureResult->getCallOffer()->getOwner() . "/" . date_format($procedureResult->getPublicationDate(), "Y") . " portant " . $procedureResult->getObject() . " de l'" . $procedureResult->getCallOffer()->getType() . " N°" . $procedureResult->getCallOffer()->getReference() . "/" . $procedureResult->getCallOffer()->getType() . "/" . $procedureResult->getCallOffer()->getOwner() . "/" . date_format($procedureResult->getCallOffer()->getPublicationDate(), "Y") . " du " . date_format($procedureResult->getCallOffer()->getPublicationDate(), "d/m/Y").".";
        } elseif ($procedureResult && $procedureResult->getExpressionInterest()) {
            return "Décision ".$procedureResult->getReference(). " : " . "N°" . $procedureResult->getReference() . "/D/" . $procedureResult->getExpressionInterest()->getOwner() . "/" . date_format($procedureResult->getPublicationDate(), "Y") . " portant " . $procedureResult->getObject() . " de l'" . $procedureResult->getExpressionInterest()->getType() . " N°" . $procedureResult->getExpressionInterest()->getReference() . "/" . $procedureResult->getExpressionInterest()->getType() . "/" . $procedureResult->getExpressionInterest()->getOwner() . "/" . date_format($procedureResult->getExpressionInterest()->getPublicationDate(), "Y") . " du " . date_format($procedureResult->getExpressionInterest()->getPublicationDate(), "d/m/Y").".";
        } else {
            return "";
        }
    }

}
