<?php

namespace Marca\DocBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\DocBundle\Entity\Markup;
use Marca\DocBundle\Form\MarkupType;

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
        $form   = $this->createForm(new MarkupType(), $markup, array(
            'em'=>$this->getDoctrine()->getEntityManager(),
        ));

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
    public function createAction($set_id)
    {
        $em = $this->getEm();
        $user = $this->getUser();

        $markup  = new Markup();
        $markup->setUser($user);
        $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($set_id);
        $markup->addMarkupset($markupset);
        $request = $this->getRequest();
        $form    = $this->createForm(new MarkupType(), $markup, array(
            'em'=>$this->getDoctrine()->getEntityManager(),
        ));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($markup);
            $em->flush();

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
     * @Route("/{id}/edit", name="markup_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$markup) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $editForm = $this->createForm(new MarkupType(), $markup, array(
            'em'=>$this->getDoctrine()->getEntityManager(),
        ));
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
     * @Route("/{id}/update", name="markup_update")
     * @Method("post")
     * @Template("MarcaDocBundle:Markup:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$markup) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $editForm   = $this->createForm(new MarkupType(), $markup, array(
            'em'=>$this->getDoctrine()->getEntityManager(),
        ));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($markup);
            $em->flush();

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
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

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
