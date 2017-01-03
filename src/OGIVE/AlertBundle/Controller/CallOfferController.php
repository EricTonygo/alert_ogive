<?php

namespace OGIVE\AlertBundle\Controller;

use OGIVE\AlertBundle\Entity\CallOffer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * CallOffer controller.
 *
 * @Route("call-offer")
 */
class CallOfferController extends Controller
{
    /**
     * Lists all callOffer entities.
     *
     * @Route("/", name="call_offer_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $callOffers = $em->getRepository('OGIVEAlertBundle:CallOffer')->getAll();

        return $this->render('OGIVEAlertBundle:callOffer:index.html.twig', array(
            'callOffers' => $callOffers,
        ));
    }

    /**
     * Creates a new callOffer entity.
     *
     * @Route("/new", name="call_offer_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $callOffer = new CallOffer();
        $form = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($callOffer);
            $em->flush($callOffer);

            return $this->redirectToRoute('callOffer_show', array('id' => $callOffer->getId()));
        }

        return $this->render('callOffer/new.html.twig', array(
            'callOffer' => $callOffer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a callOffer entity.
     *
     * @Route("/{id}", name="call_offer_show")
     * @Method("GET")
     */
    public function showAction(CallOffer $callOffer)
    {
        $deleteForm = $this->createDeleteForm($callOffer);

        return $this->render('callOffer/show.html.twig', array(
            'callOffer' => $callOffer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing callOffer entity.
     *
     * @Route("/{id}/edit", name="call_offer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CallOffer $callOffer)
    {
        $deleteForm = $this->createDeleteForm($callOffer);
        $editForm = $this->createForm('OGIVE\AlertBundle\Form\CallOfferType', $callOffer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('callOffer_edit', array('id' => $callOffer->getId()));
        }

        return $this->render('callOffer/edit.html.twig', array(
            'callOffer' => $callOffer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a callOffer entity.
     *
     * @Route("/{id}", name="call_offer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CallOffer $callOffer)
    {
        $form = $this->createDeleteForm($callOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($callOffer);
            $em->flush($callOffer);
        }

        return $this->redirectToRoute('callOffer_index');
    }

    /**
     * Creates a form to delete a callOffer entity.
     *
     * @param CallOffer $callOffer The callOffer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CallOffer $callOffer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('callOffer_delete', array('id' => $callOffer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
