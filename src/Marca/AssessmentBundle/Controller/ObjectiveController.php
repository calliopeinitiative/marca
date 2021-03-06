<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssessmentBundle\Entity\Objective;
use Marca\AssessmentBundle\Form\ObjectiveType;

/**
 * Objective controller.
 *
 * @Route("/objective")
 */
class ObjectiveController extends Controller
{


    /**
     * Displays a form to create a new Objective entity.
     *
     * @Route("/{assessmentsetid}/new", name="objective_new")
     */
    public function newAction($assessmentsetid)
    {
        
        $objective = new Objective();
        $objective->setDescription('<p></p>');
        $objective->setObjective('<p></p>');
        $form   = $this->createForm(new ObjectiveType(), $objective);

        return $this->render('MarcaAssessmentBundle:Objective:new.html.twig', array(
            'objective' => $objective,
            'assessmentsetid' => $assessmentsetid,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Objective entity.
     *
     * @Route("/{assessmentsetid}/create", name="objective_create")
     * @Method("post")
     */
    public function createAction($assessmentsetid)
    {
        $em = $this->getEm();
        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->find($assessmentsetid);
        
        $objective  = new Objective();
        $objective->setAssessmentset($assessmentset);
        $request = $this->getRequest();
        $form    = $this->createForm(new ObjectiveType(), $objective);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($objective);
            $em->flush();

            return $this->redirect($this->generateUrl('assessmentset_show', array('id' => $assessmentsetid)));
            
        }

        return $this->render('MarcaAssessmentBundle:Objective:new.html.twig', array(
            'objective' => $objective,
            'assessmentsetid' => $assessmentsetid,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Objective entity.
     *
     * @Route("/{id}/edit", name="objective_edit")
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $objective = $em->getRepository('MarcaAssessmentBundle:Objective')->find($id);

        if (!$objective) {
            throw $this->createNotFoundException('Unable to find Objective entity.');
        }

        $editForm = $this->createForm(new ObjectiveType(), $objective);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaAssessmentBundle:Objective:edit.html.twig', array(
            'objective'      => $objective,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Objective entity.
     *
     * @Route("/{id}/update", name="objective_update")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Objective:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $objective = $em->getRepository('MarcaAssessmentBundle:Objective')->find($id);
        $assessmentsetid = $objective->getAssessmentset()->getId();
        if (!$objective) {
            throw $this->createNotFoundException('Unable to find Objective entity.');
        }

        $editForm   = $this->createForm(new ObjectiveType(), $objective);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($objective);
            $em->flush();

            return $this->redirect($this->generateUrl('assessmentset_show', array('id' => $assessmentsetid)));
        }

        return $this->render('MarcaAssessmentBundle:Objective:edit.html.twig', array(
            'objective'      => $objective,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Objective entity.
     *
     * @Route("/{id}/delete", name="objective_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $objective = $em->getRepository('MarcaAssessmentBundle:Objective')->find($id);
            $assessmentsetid = $objective->getAssessmentset()->getId();
            if (!$objective) {
                throw $this->createNotFoundException('Unable to find Objective entity.');
            }

            $em->remove($objective);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('assessmentset_show', array('id' => $assessmentsetid)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
