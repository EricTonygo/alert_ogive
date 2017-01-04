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
class DomainController extends Controller
{
    
    /**
     * @Rest\View()
     * @Rest\Get("/domains" , name="domain_index", options={ "method_prefix" = false })
     * @param Request $request
     */
    public function getDomainsAction(Request $request){
        
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
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
//            if($repositoryDomain->findBy(array('name' => $domain->getName(), 'status' => 1))){
//                return new JsonResponse(["success" => false, 'message' => 'Un domaine avec ce nom existe dejà'], Response::HTTP_NOT_ACCEPTABLE);
//            }
            $domain = $repositoryDomain->saveDomain($domain);
            $domain_content_grid = $this->renderView('OGIVEAlertBundle:domain:domain-grid.html.twig', array('domain' => $domain));
            $domain_content_list = $this->renderView('OGIVEAlertBundle:domain:domain-list.html.twig', array('domain' => $domain));
            /* @var $domain Domain */
            $view = View::create(["code" => 200, 'domain' => $domain, 'domain_content_grid' => $domain_content_grid, 'domain_content_list' => $domain_content_list]);
            $view->setFormat('json');
            return $view;
            //return new JsonResponse(["success" => true, 'domain' => $domain, 'domain_content_grid' => $domain_content_grid, 'domain_content_list' => $domain_content_list], Response::HTTP_OK);
        }else{
            $view = View::create($form);
            $view->setFormat('json');
            return $view;
        }
    }
    
    
    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/domains/{id}")
     */
    public function removeDomainAction(Domain $domain) {
       
        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');
        if($domain){
            $repositoryDomain->removeDomain($domain);
            return new JsonResponse(["message" => 'Domaine supprimé avec succès']); 
        }else{
            return new JsonResponse(["message" => 'Domain introuvable'], Response::HTTP_NOT_FOUND);
        }
    }
    
    /**
     * @Rest\View()
     * @Rest\Put("/domains/{id}")
     */
    public function putDomainAction(Request $request, Domain $domain) {
        
        return $this->updateDomainAction($request, $domain);
    }
    
    /**
     * @Rest\View()
     * @Rest\Patch("/domains/{id}")
     */
    public function patchDomainAction(Request $request, Domain $domain) {
        
        return $this->updatePlaceAction($request, $domain);
    }
    
    public function updateDomainAction(Request $request, Domain $domain) {
        
        $repositoryDomain = $this->getDoctrine()->getManager()->getRepository('OGIVEAlertBundle:Domain');
        
        if(empty($domain)){
            return new JsonResponse(['message' => 'Domaine introuvable'], Response::HTTP_NOT_FOUND);
        }
        
        $form = $this->createForm('OGIVE\AlertBundle\Form\DomainType', $domain);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if($form->isValid()){
                $domain = $repositoryDomain->updateDomain($domain);
                $domain_content_grid = $this->renderView('OGIVEAlertBundle:domain:domain-grid.html.twig', array('domain' => $domain));
                $domain_content_list = $this->renderView('OGIVEAlertBundle:domain:domain-list.html.twig', array('domain' => $domain));
                /* @var $domain Domain */
                $view = View::create(["code" => 200, 'domain' => $domain, 'domain_content_grid' => $domain_content_grid, 'domain_content_list' => $domain_content_list]);
                $view->setFormat('json');
                return $view;
            }else{
               return $form;
            }
        } else {
            $edit_domain_form = $this->renderView('OGIVEAlertBundle:domain:edit.html.twig', array('form' => $form->createView(), 'domain' => $domain));
            /* @var $domain Domain */
            $view = View::create(["code" => 200, 'domain' => $domain, 'edit_domain_form' => $edit_domain_form]);
            $view->setFormat('json');
            return $view;
        }
    }
    
    
    /**
    * Creates a new domain entity.
    *
    * @Route("/domain-new", name="domain_new")
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
     * @Route("/domain-show/{id}", name="domain_show")
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
     * @Route("/domain-edit/{id}", name="domain_edit")
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
     * @Route("/domain-delete/{id}", name="domain_delete")
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