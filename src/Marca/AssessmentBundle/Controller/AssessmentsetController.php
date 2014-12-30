<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssessmentBundle\Entity\Assessmentset;
use Marca\AssessmentBundle\Form\AssessmentsetType;

/**
 * Assessmentset controller.
 *
 * @Route("/assessmentset")
 */
class AssessmentsetController extends Controller
{
    /**
     * Lists all Assessmentset entities.
     *
     * @Route("/", name="assessmentset")
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $assessmentsets = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->findAll();
        $scales = $em->getRepository('MarcaAssessmentBundle:Scale')->findAll();
        return $this->render('MarcaAssessmentBundle:Assessmentset:index.html.twig', array(
            'assessmentsets' => $assessmentsets,
            'scales' => $scales
        ));
    }

    /**
     * Finds and displays a Assessmentset entity.
     *
     * @Route("/{id}/show", name="assessmentset_show")
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->find($id);

        if (!$assessmentset) {
            throw $this->createNotFoundException('Unable to find Assessmentset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaAssessmentBundle:Assessmentset:show.html.twig', array(
            'assessmentset'      => $assessmentset,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Assessmentset entity.
     *
     * @Route("/new", name="assessmentset_new")
     */
    public function newAction()
    {
        $assessmentset = new Assessmentset();
        $form   = $this->createForm(new AssessmentsetType(), $assessmentset);

        return $this->render('MarcaAssessmentBundle:Assessmentset:new.html.twig', array(
            'assessmentset' => $assessmentset,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Assessmentset entity.
     *
     * @Route("/create", name="assessmentset_create")
     * @Method("post")
     */
    public function createAction()
    {
        $assessmentset  = new Assessmentset();
        $request = $this->getRequest();
        $form    = $this->createForm(new AssessmentsetType(), $assessmentset);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($assessmentset);
            $em->flush();

            return $this->redirect($this->generateUrl('assessmentset'));
            
        }

        return $this->render('MarcaAssessmentBundle:Assessmentset:new.html.twig', array(
            'assessmentset' => $assessmentset,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Assessmentset entity.
     *
     * @Route("/{id}/edit", name="assessmentset_edit")
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->find($id);

        if (!$assessmentset) {
            throw $this->createNotFoundException('Unable to find Assessmentset entity.');
        }

        $editForm = $this->createForm(new AssessmentsetType(), $assessmentset);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaAssessmentBundle:Assessmentset:edit.html.twig', array(
            'assessmentset'      => $assessmentset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Assessmentset entity.
     *
     * @Route("/{id}/update", name="assessmentset_update")
     * @Method("post")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->find($id);

        if (!$assessmentset) {
            throw $this->createNotFoundException('Unable to find Assessmentset entity.');
        }

        $editForm   = $this->createForm(new AssessmentsetType(), $assessmentset);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($assessmentset);
            $em->flush();

            return $this->redirect($this->generateUrl('assessmentset'));
        }

        return $this->render('MarcaAssessmentBundle:Assessmentset:edit.html.twig', array(
            'assessmentset'      => $assessmentset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Assessmentset entity.
     *
     * @Route("/{id}/delete", name="assessmentset_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->find($id);

            if (!$assessmentset) {
                throw $this->createNotFoundException('Unable to find Assessmentset entity.');
            }

            $em->remove($assessmentset);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('assessmentset'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
