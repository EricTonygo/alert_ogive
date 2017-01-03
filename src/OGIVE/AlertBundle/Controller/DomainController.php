<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\Domain;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Domain controller.
 *
 */
class DomainController extends Controller
{
    /**
     * Lists all domain entities.
     *
     * @Route("/domains", name="domain_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $domain = new Domain();
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain);

        $domains = $em->getRepository('OGIVEAlertBundle:Domain')->findAll();

        return $this->render('OGIVEAlertBundle:domain:index.html.twig', array(
            'domains' => $domains,
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/domains")
     */
    public function postDomainsAction(Request $request) {
        $domain = new Domain();
        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain);
        $form->submit($request->request->all());
        
        if($form->isValid()){
            if($repositoryDomain->findBy(array('name' => $domain->getName(), 'status' => 1))){
                return new JsonResponse(["success" => false, 'message' => 'Un domaine avec ce nom existe dejà'], Response::HTTP_NOT_ACCEPTABLE);
            }
            $domain = $repositoryDomain->saveDomain($domain);
            $domain_content_grid = $this->renderView('OGIVEAlertBundle:domain:domain-grid.html.twig', array('domain' => $domain));
            $domain_content_list = $this->renderView('OGIVEAlertBundle:domain:domain-list.html.twig', array('domain' => $domain));
            return new JsonResponse(["success" => true, 'domain_content_grid' => $domain_content_grid, 'domain_content_list' => $domain_content_list], Response::HTTP_OK);
        }else{
            $url = $this->get('router')->generate('call_offer_index');
            $response = new RedirectResponse($url);
            return $response;
        }
    }
    

    /**
     * Creates a new domain entity.
     *
     * @Route("/new", name="domain_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $domain = new Domain();
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($domain);
            $em->flush($domain);

            return $this->redirectToRoute('domain_show', array('id' => $domain->getId()));
        }

        return $this->render('domain/new.html.twig', array(
            'domain' => $domain,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a domain entity.
     *
     * @Route("/{id}", name="domain_show")
     * @Method("GET")
     */
    public function showAction(Domain $domain)
    {
        $deleteForm = $this->createDeleteForm($domain);

        return $this->render('domain/show.html.twig', array(
            'domain' => $domain,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing domain entity.
     *
     * @Route("/{id}/edit", name="domain_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Domain $domain)
    {
        $deleteForm = $this->createDeleteForm($domain);
        $editForm = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('domain_edit', array('id' => $domain->getId()));
        }

        return $this->render('domain/edit.html.twig', array(
            'domain' => $domain,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a domain entity.
     *
     * @Route("/{id}", name="domain_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Domain $domain)
    {
        $form = $this->createDeleteForm($domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($domain);
            $em->flush($domain);
        }

        return $this->redirectToRoute('domain_index');
    }

    /**
     * Creates a form to delete a domain entity.
     *
     * @param Domain $domain The domain entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Domain $domain)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('domain_delete', array('id' => $domain->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
