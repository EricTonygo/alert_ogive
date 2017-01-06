<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Domain;
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
 * Domain controller.
 *
 */
class DomainController extends Controller {

    /**
     * @Rest\View()
     * @Rest\Get("/domains" , name="domain_index", options={ "method_prefix" = false })
     * @param Request $request
     */
    public function getDomainsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $domain = new Domain();
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain);
        $domains = $em->getRepository('OGIVEAlertBundle:Domain')->getAll();
        return $this->render('OGIVEAlertBundle:domain:index.html.twig', array(
                    'domains' => $domains,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Rest\View()
     * @Rest\Get("/domains/{id}" , name="domain_get_one", options={ "method_prefix" = false })
     */
    public function getDomainByIdAction(Domain $domain) {
        if (empty($domain)) {
            return new JsonResponse(['message' => 'Domaine introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain, array('method' => 'PUT'));
        $domain_details = $this->renderView('OGIVEAlertBundle:domain:show.html.twig', array(
            'domain' => $domain,
            'form' => $form->createView()
        ));
        $view = View::create(["code" => 200, 'domain' => $domain, 'domain_details' => $domain_details]);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/domains")
     */
    public function postDomainsAction(Request $request) {
        $domain = new Domain();
        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($repositoryDomain->findOneBy(array('name' => $domain->getName(), 'status' => 1))) {
                return new JsonResponse(["success" => false, 'message' => 'Un domaine avec ce nom existe dejà'], Response::HTTP_BAD_REQUEST);
            }
            $domain = $repositoryDomain->saveDomain($domain);
            $domain_content_grid = $this->renderView('OGIVEAlertBundle:domain:domain-grid.html.twig', array('domain' => $domain));
            $domain_content_list = $this->renderView('OGIVEAlertBundle:domain:domain-list.html.twig', array('domain' => $domain));
            /* @var $domain Domain */
            $view = View::create(["code" => 200, 'domain' => $domain, 'domain_content_grid' => $domain_content_grid, 'domain_content_list' => $domain_content_list]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'domain' => $domain, 'domain_content_grid' => $domain_content_grid, 'domain_content_list' => $domain_content_list], Response::HTTP_OK);
        } else {
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/domains/{id}")
     */
    public function removeDomainAction(Domain $domain) {

        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');
        if ($domain) {
            $repositoryDomain->deleteDomain($domain);
            $view = View::create(['domain' => $domain, "message" => 'Domaine supprimé avec succès']);
            $view->setFormat('json');
            return $view;
        } else {
            return new JsonResponse(["message" => 'Domain introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/domains/{id}", name="domain_update", options={ "method_prefix" = false })
     * @param Request $request
     */
    public function putDomainAction(Request $request, Domain $domain) {

        return $this->updateDomainAction($request, $domain);
    }

    public function updateDomainAction(Request $request, Domain $domain) {

        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');

        if (empty($domain)) {
            return new JsonResponse(['message' => 'Domaine introuvable'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domainUnique = $repositoryDomain->findOneBy(array('name' => $domain->getName(), 'status' => 1));
            if ($domainUnique && $domainUnique->getId() != $domain->getId()) {
                return new JsonResponse(["success" => false, 'message' => 'Un domaine avec ce nom existe dejà'], Response::HTTP_NOT_FOUND);
            }
            $domain = $repositoryDomain->updateDomain($domain);
            $domain_content_grid = $this->renderView('OGIVEAlertBundle:domain:domain-grid-edit.html.twig', array('domain' => $domain));
            $domain_content_list = $this->renderView('OGIVEAlertBundle:domain:domain-list-edit.html.twig', array('domain' => $domain));
            /* @var $domain Domain */
            $view = View::create(["code" => 200, 'domain' => $domain, 'domain_content_grid' => $domain_content_grid, 'domain_content_list' => $domain_content_list]);
            $view->setFormat('json');
            return $view;
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return $form;
        } else {
            $edit_domain_form = $this->renderView('OGIVEAlertBundle:domain:edit.html.twig', array('form' => $form->createView(), 'domain' => $domain));
            /* @var $domain Domain */
            $view = View::create(["code" => 200, 'domain' => $domain, 'edit_domain_form' => $edit_domain_form]);
            $view->setFormat('json');
            return $view;
        }
    }

}
