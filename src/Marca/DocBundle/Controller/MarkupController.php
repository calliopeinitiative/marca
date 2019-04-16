<?php

namespace Marca\DocBundle\Controller;

use Marca\DocBundle\Entity\Markup;
use Marca\DocBundle\Form\MarkupType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Markup controller.
 *
 * @Route("/markup")
 */
class MarkupController extends Controller
{
    /**
     * Lists all Markup entities.
     *
     * @Route("/", name="markup")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->findAll();

        return array('markup' => $markup);
    }

    /**
     * Finds and displays a Markup entity.
     *
     * @Route("/{id}/show", name="markup_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$markup) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'markup'      => $markup,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Markup entity.
     *
     * @Route("/{set_id}/new", name="markup_new")
     * @Template()
     */
    public function newAction($set_id)
    {
        $markup = new Markup();
        $form   = $this->createForm(new MarkupType(), $markup);

        return array(
            'set_id' => $set_id,
            'markup' => $markup,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Markup entity.
     *
     * @Route("/{set_id}/create", name="markup_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Markup:new.html.twig")
     */
    public function createAction(Request $request, $set_id)
    {
        $em = $this->getEm();
        $user = $this->getUser();

        $markup  = new Markup();
        $markup->setUser($user);
        $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($set_id);
        $markup->addMarkupset($markupset);

        $form   = $this->createForm(new MarkupType(), $markup);
        $form->handleRequest($request);

        $name = $markup->getName();
        $name = preg_replace('/[^[:alpha:] ]/', '', $name);
        $markup->setValue(preg_replace('/ /', '_', $name));
        
        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($markup);
            $em->flush();

            $this->get('session')->getFlashBag()->add('update',$set_id);
            return $this->redirect($this->generateUrl('markupset'));
            
        }

        return array(
            'markup' => $markup,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Markup entity.
     *
     * @Route("/{set_id}/{id}/edit", name="markup_edit")
     * @Template()
     */
    public function editAction($id, $set_id)
    {
        $em = $this->getEm();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$markup) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $editForm = $this->createForm(new MarkupType(), $markup);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'markup'      => $markup,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Markup entity.
     *
     * @Route("/{set_id}/{id}/update", name="markup_update")
     * @Method("post")
     * @Template("MarcaDocBundle:Markup:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $set_id)
    {
        $em = $this->getEm();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$markup) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $editForm = $this->createForm(new MarkupType(), $markup);
        $deleteForm = $this->createDeleteForm($id);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($markup);
            $em->flush();

            $this->get('session')->getFlashBag()->add('update',$set_id);
            return $this->redirect($this->generateUrl('markupset'));
        }

        return array(
            'markup'      => $markup,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Markup entity.
     *
     * @Route("/{id}/delete", name="markup_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $markup = $em->getRepository('MarcaDocBundle:Markup')->find($id);

            if (!$markup) {
                throw $this->createNotFoundException('Unable to find Markup entity.');
            }

            $em->remove($markup);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('markupset'));
    }
    
   

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
