<?php

namespace Marca\DocBundle\Controller;

use Marca\DocBundle\Entity\Markup;
use Marca\DocBundle\Form\MarkupType;
use Marca\HomeBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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

        return $this->render('MarcaDocBundle:Markup:index.html.twig',array(
            'markup' => $markup
        ));
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

        return $this->render('MarcaDocBundle:Markup:index.html.twig',array(
            'markup'      => $markup,
            'delete_form' => $deleteForm->createView(),
        ));
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
        $form   = $this->createForm(MarkupType::class, $markup);

        return $this->render('MarcaDocBundle:Markup:new.html.twig',array(
            'set_id' => $set_id,
            'markup' => $markup,
            'form'   => $form->createView()
        ));

    }

    /**
     * Creates a new Markup entity.
     *
     * @Route("/{set_id}/create", name="markup_create")
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

        $form   = $this->createForm(MarkupType::class, $markup);
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

        return $this->render('MarcaDocBundle:Markup:new.html.twig',array(
            'markup' => $markup,
            'form'   => $form->createView()
        ));
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

        $editForm = $this->createForm(MarkupType::class, $markup);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaDocBundle:Markup:edit.html.twig',array(
            'markup'      => $markup,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

    }

    /**
     * Edits an existing Markup entity.
     *
     * @Route("/{set_id}/{id}/update", name="markup_update")
     * @Template("MarcaDocBundle:Markup:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $set_id)
    {
        $em = $this->getEm();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$markup) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $editForm = $this->createForm(MarkupType::class, $markup);
        $deleteForm = $this->createDeleteForm($id);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($markup);
            $em->flush();

            $this->get('session')->getFlashBag()->add('update',$set_id);
            return $this->redirect($this->generateUrl('markupset'));
        }

        return $this->render('MarcaDocBundle:Markup:edit.html.twig',array(
            'markup'      => $markup,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Markup entity.
     *
     * @Route("/{id}/delete", name="markup_delete")
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
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
