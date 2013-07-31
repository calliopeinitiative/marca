<?php

namespace Marca\AssignmentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssignmentBundle\Entity\PromptItem;
use Marca\AssignmentBundle\Form\PromptItemType;

/**
 * PromptItem controller.
 *
 * @Route("/promptitem")
 */
class PromptItemController extends Controller
{
    /**
     * Lists all PromptItem entities.
     *
     * @Route("/", name="promptitem")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcaAssignmentBundle:PromptItem')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a PromptItem entity.
     *
     * @Route("/{id}/show", name="promptitem_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PromptItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new PromptItem entity.
     *
     * @Route("/{reviewrubricid}/new", name="promptitem_new")
     * @Template()
     */
    public function newAction($reviewrubricid)
    {
        $em = $this->getDoctrine()->getManager();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($reviewrubricid);
        
        $promptitem = new PromptItem();
        $promptitem->setReviewRubric($reviewrubric);
        $promptitem->setType(0);
        $form   = $this->createForm(new PromptItemType(), $promptitem);

        return array(
            'promptitem' => $promptitem,
            'reviewrubricid' => $reviewrubricid,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new PromptItem entity.
     *
     * @Route("/{reviewrubricid}/create", name="promptitem_create")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:PromptItem:new.html.twig")
     */
    public function createAction(Request $request,$reviewrubricid)
    {
        $em = $this->getDoctrine()->getManager();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($reviewrubricid);
        
        $promptitem = new PromptItem();
        $promptitem->setReviewRubric($reviewrubric);
       
        $form = $this->createForm(new PromptItemType(), $promptitem);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promptitem);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $reviewrubricid)));
        }

        return array(
            'promptitem' => $promptitem,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PromptItem entity.
     *
     * @Route("/{id}/edit", name="promptitem_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PromptItem entity.');
        }

        $editForm = $this->createForm(new PromptItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing PromptItem entity.
     *
     * @Route("/{id}/update", name="promptitem_update")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:PromptItem:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PromptItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PromptItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('promptitem_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a PromptItem entity.
     *
     * @Route("/{id}/delete", name="promptitem_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PromptItem entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('promptitem'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
