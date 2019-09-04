<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\AssessmentBundle\Entity\Scale;
use Marca\AssessmentBundle\Form\ScaleType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $scales = $em->getRepository('MarcaAssessmentBundle:Scale')->findAll();

        return $this->render('MarcaAssessmentBundle:Scale:index.html.twig', array(
            'scales' => $scales
        ));
    }

    /**
     * Finds and displays a Scale entity.
     *
     * @Route("/{id}/show", name="scale_show")
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($id);

        if (!$scale) {
            throw $this->createNotFoundException('Unable to find Scale entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaAssessmentBundle:Scale:show.html.twig', array(
            'scale'      => $scale,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Scale entity.
     *
     * @Route("/new", name="scale_new")
     */
    public function newAction()
    {
        $scale = new Scale();
        $form   = $this->createForm(new ScaleType(), $scale);

        return $this->render('MarcaAssessmentBundle:Scale:new.html.twig', array(
            'scale' => $scale,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Scale entity.
     *
     * @Route("/create", name="scale_create")
     * @Method("post")
     */
    public function createAction()
    {
        $scale  = new Scale();
        $request = $this->getRequest();
        $form    = $this->createForm(new ScaleType(), $scale);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($scale);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $scale->getId())));
            
        }

        return $this->render('MarcaAssessmentBundle:Scale:new.html.twig', array(
            'scale' => $scale,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Scale entity.
     *
     * @Route("/{id}/edit", name="scale_edit")
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($id);

        if (!$scale) {
            throw $this->createNotFoundException('Unable to find Scale entity.');
        }

        $editForm = $this->createForm(new ScaleType(), $scale);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaAssessmentBundle:Scale:edit.html.twig', array(
            'scale'      => $scale,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Scale entity.
     *
     * @Route("/{id}/update", name="scale_update")
     * @Method("post")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($id);

        if (!$scale) {
            throw $this->createNotFoundException('Unable to find Scale entity.');
        }

        $editForm   = $this->createForm(new ScaleType(), $scale);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($scale);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $id)));
        }

        return $this->render('MarcaAssessmentBundle:Scale:edit.html.twig', array(
            'scale'      => $scale,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
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
