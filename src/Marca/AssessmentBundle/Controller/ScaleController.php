<?php

namespace Marca\AssessmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssessmentBundle\Entity\Scale;
use Marca\AssessmentBundle\Form\ScaleType;

/**
 * Scale controller.
 *
 * @Route("/scale")
 */
class ScaleController extends Controller
{
    /**
     * Lists all Scale entities.
     *
     * @Route("/", name="scale")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $scales = $em->getRepository('MarcaAssessmentBundle:Scale')->findAll();

        return array('scales' => $scales);
    }

    /**
     * Finds and displays a Scale entity.
     *
     * @Route("/{id}/show", name="scale_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($id);

        if (!$scale) {
            throw $this->createNotFoundException('Unable to find Scale entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'scale'      => $scale,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Scale entity.
     *
     * @Route("/new", name="scale_new")
     * @Template()
     */
    public function newAction()
    {
        $scale = new Scale();
        $form   = $this->createForm(new ScaleType(), $scale);

        return array(
            'scale' => $scale,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Scale entity.
     *
     * @Route("/create", name="scale_create")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Scale:new.html.twig")
     */
    public function createAction()
    {
        $scale  = new Scale();
        $request = $this->getRequest();
        $form    = $this->createForm(new ScaleType(), $scale);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($scale);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $scale->getId())));
            
        }

        return array(
            'scale' => $scale,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Scale entity.
     *
     * @Route("/{id}/edit", name="scale_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($id);

        if (!$scale) {
            throw $this->createNotFoundException('Unable to find Scale entity.');
        }

        $editForm = $this->createForm(new ScaleType(), $scale);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'scale'      => $scale,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Scale entity.
     *
     * @Route("/{id}/update", name="scale_update")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Scale:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($id);

        if (!$scale) {
            throw $this->createNotFoundException('Unable to find Scale entity.');
        }

        $editForm   = $this->createForm(new ScaleType(), $scale);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($scale);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $id)));
        }

        return array(
            'scale'      => $scale,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Scale entity.
     *
     * @Route("/{id}/delete", name="scale_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($id);

            if (!$scale) {
                throw $this->createNotFoundException('Unable to find Scale entity.');
            }

            $em->remove($scale);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('scale'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
