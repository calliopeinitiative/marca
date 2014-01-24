<?php

namespace Marca\AssignmentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Marca\HomeBundle\Controller\Controller;
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
        $em = $this->getEm();

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
        $em = $this->getEm();

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
        $em = $this->getEm();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($reviewrubricid);

        $promptitem = new PromptItem();

        $promptitem->setType(0);

        $form   = $this->createForm(new PromptItemType(), $promptitem);

        return array(
            'promptitem' => $promptitem,
            'reviewrubric' => $reviewrubric,
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
        $em = $this->getEm();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($reviewrubricid);
        $count = count($reviewrubric->getPromptItems());
        
        $promptitem = new PromptItem();
        $promptitem->setReviewRubric($reviewrubric);
        $promptitem->setSortOrder($count + 1);
       
        $form = $this->createForm(new PromptItemType(), $promptitem);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
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
        $em = $this->getEm();

        $promptitem = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);

        if (!$promptitem) {
            throw $this->createNotFoundException('Unable to find PromptItem entity.');
        }

        $editForm = $this->createForm(new PromptItemType(), $promptitem);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'promptitem'      => $promptitem,
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
        $em = $this->getEm();

        $entity = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PromptItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PromptItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $rubricid=$entity->getReviewRubric()->getid();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $rubricid)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Moves a Project entity up one in the display order.
     *
     * @Route("/{promptitemId}/{previousPromptitemId}/promote", name="promptitem_promote")
     */
    public function promoteAction($promptitemId, $previousPromptitemId)
    {

        $em = $this->getEm();

        $promptitem = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($promptitemId);
        $currentOrder = $promptitem->getSortOrder();
        $previousPromptitem = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($previousPromptitemId);
        $previousOrder = $previousPromptitem->getSortOrder();
        $promptitem->setSortOrder($previousOrder);
        $previousPromptitem->setSortOrder($currentOrder);
        $em->persist($promptitem);
        $em->persist($previousPromptitem);
        $em->flush();

        $reviewRubricId = $promptitem->getReviewRubric()->getId();

        return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $reviewRubricId)));

    }

    /**
     * Moves a Project entity down one in the display order.
     *
     * @Route("/{promptitemId}/{followingPromptitemId}/demote", name="promptitem_demote")
     */
    public function demoteAction($promptitemId, $followingPromptitemId)
    {

        $em = $this->getEm();

        $promptitem = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($promptitemId);
        $currentOrder = $promptitem->getSortOrder();
        $followingPromptitem = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($followingPromptitemId);
        $followingOrder = $followingPromptitem->getSortOrder();
        $promptitem->setSortOrder($followingOrder);
        $followingPromptitem->setSortOrder($currentOrder);
        $em->persist($promptitem);
        $em->persist($followingPromptitem);
        $em->flush();

        $reviewRubricId = $promptitem->getReviewRubric()->getId();

        return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $reviewRubricId)));

    }    

    /**
     * Displays a form to delete an existing PromptItem entity.
     *
     * @Route("/{id}/delete_modal", name="promptitem_delete_modal")
     * @Template()
     */
    public function delete_modalAction($id)
    {
        $em = $this->getEm();

        $promptitem = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);

        if (!$promptitem) {
            throw $this->createNotFoundException('Unable to find PromptItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'promptitem'      => $promptitem,
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
            $em = $this->getEm();
            $entity = $em->getRepository('MarcaAssignmentBundle:PromptItem')->find($id);
            $reviewrubricid = $entity->getReviewRubric()->getId();

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PromptItem entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $reviewrubricid)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
