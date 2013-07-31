<?php

namespace Marca\AssignmentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssignmentBundle\Entity\ReviewRubric;
use Marca\AssignmentBundle\Form\ReviewRubricType;

/**
 * ReviewRubric controller.
 *
 * @Route("/reviewrubric")
 */
class ReviewRubricController extends Controller
{
    /**
     * Lists all ReviewRubric entities.
     *
     * @Route("/", name="reviewrubric")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a ReviewRubric entity.
     *
     * @Route("/{id}/show", name="reviewrubric_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new ReviewRubric entity.
     *
     * @Route("/new", name="reviewrubric_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ReviewRubric();
        $form   = $this->createForm(new ReviewRubricType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ReviewRubric entity.
     *
     * @Route("/create", name="reviewrubric_create")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:ReviewRubric:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ReviewRubric();
        $form = $this->createForm(new ReviewRubricType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReviewRubric entity.
     *
     * @Route("/{id}/edit", name="reviewrubric_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
        }

        $editForm = $this->createForm(new ReviewRubricType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ReviewRubric entity.
     *
     * @Route("/{id}/update", name="reviewrubric_update")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:ReviewRubric:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReviewRubricType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewrubric_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ReviewRubric entity.
     *
     * @Route("/{id}/delete", name="reviewrubric_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reviewrubric'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
