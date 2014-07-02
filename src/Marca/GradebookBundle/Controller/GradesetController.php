<?php

namespace Marca\GradebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\GradebookBundle\Entity\Gradeset;
use Marca\GradebookBundle\Form\GradesetType;

/**
 * Gradeset controller.
 *
 * @Route("/gradeset")
 */
class GradesetController extends Controller
{

    /**
     * Lists all Gradeset entities.
     *
     * @Route("/", name="gradeset")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gradesets = $em->getRepository('MarcaGradebookBundle:Gradeset')->findAll();

        return array(
            'gradesets' => $gradesets,
        );
    }
    /**
     * Creates a new Gradeset entity.
     *
     * @Route("/", name="gradeset_create")
     * @Method("POST")
     * @Template("MarcaGradebookBundle:Gradeset:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $gradeset = new Gradeset();
        $form = $this->createCreateForm($gradeset);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gradeset);
            $em->flush();

            return $this->redirect($this->generateUrl('gradeset_show', array('id' => $gradeset->getId())));
        }

        return array(
            'gradeset' => $gradeset,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Gradeset entity.
     *
     * @param Gradeset $gradeset The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Gradeset $gradeset)
    {
        $form = $this->createForm(new GradesetType(), $gradeset, array(
            'action' => $this->generateUrl('gradeset_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Gradeset entity.
     *
     * @Route("/new", name="gradeset_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $gradeset = new Gradeset();
        $form   = $this->createCreateForm($gradeset);

        return array(
            'gradeset' => $gradeset,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Gradeset entity.
     *
     * @Route("/{id}", name="gradeset_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

        if (!$gradeset) {
            throw $this->createNotFoundException('Unable to find Gradeset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'gradeset'      => $gradeset,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Gradeset entity.
     *
     * @Route("/{id}/edit", name="gradeset_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

        if (!$gradeset) {
            throw $this->createNotFoundException('Unable to find Gradeset entity.');
        }

        $editForm = $this->createEditForm($gradeset);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'gradeset'      => $gradeset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Gradeset entity.
    *
    * @param Gradeset $gradeset The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Gradeset $gradeset)
    {
        $form = $this->createForm(new GradesetType(), $gradeset, array(
            'action' => $this->generateUrl('gradeset_update', array('id' => $gradeset->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Gradeset entity.
     *
     * @Route("/{id}", name="gradeset_update")
     * @Method("PUT")
     * @Template("MarcaGradebookBundle:Gradeset:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

        if (!$gradeset) {
            throw $this->createNotFoundException('Unable to find Gradeset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($gradeset);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('gradeset_edit', array('id' => $id)));
        }

        return array(
            'gradeset'      => $gradeset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Gradeset entity.
     *
     * @Route("/{id}", name="gradeset_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

            if (!$gradeset) {
                throw $this->createNotFoundException('Unable to find Gradeset entity.');
            }

            $em->remove($gradeset);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gradeset'));
    }

    /**
     * Creates a form to delete a Gradeset entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gradeset_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
