<?php

namespace Marca\GradebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\GradebookBundle\Entity\Attendance;
use Marca\GradebookBundle\Form\AttendanceType;

/**
 * Attendance controller.
 *
 * @Route("/attendance")
 */
class AttendanceController extends Controller
{

    /**
     * Lists all Attendance entities.
     *
     * @Route("/", name="attendance")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $entities = $em->getRepository('MarcaGradebookBundle:Attendance')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Attendance entity.
     *
     * @Route("/{courseid}/{id}/{type}/create", name="attendance_create_ajax")
     * @Method("GET")
     * @Template("MarcaGradebookBundle:Attendance:new_ajax.html.twig")
     */
    public function createAjaxAction($type, $id)
    {
        $em = $this->getEm();
        $attendance = new Attendance();
        $today= date_create();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);
        $attendance->setDate($today);
        $attendance->setType($type);
        $attendance->setRoll($roll);
        $em->persist($attendance);
        $em->flush();

        return $this->redirect($this->generateUrl('attendance_show_ajax', array('rollid' => $id)));

    }


    /**
     * Finds and displays a Attendance entity.
     *
     * @Route("/{rollid}", name="attendance_show_ajax")
     * @Method("GET")
     * @Template("MarcaGradebookBundle:Attendance:show_ajax.html.twig")
     */
    public function showAjaxAction($rollid)
    {
        $em = $this->getEm();
        $absences = $em->getRepository('MarcaGradebookBundle:Attendance')->countAbsenses($rollid);
        $tardies = $em->getRepository('MarcaGradebookBundle:Attendance')->countTardies($rollid);

        return array(
            'tardies'      => $tardies,
            'absences'      => $absences,
        );
    }

    /**
     * Displays a form to edit an existing Attendance entity.
     *
     * @Route("/{courseid}/{id}/{rollid}/{user}/edit", name="attendance_edit")
     * @Method("GET")
     * @Template("MarcaGradebookBundle:Attendance:edit_modal.html.twig")
     */
    public function editAction($id, $courseid, $rollid, $user)
    {
        $em = $this->getEm();

        $attendance = $em->getRepository('MarcaGradebookBundle:Attendance')->find($id);

        if (!$attendance) {
            throw $this->createNotFoundException('Unable to find Attendance entity.');
        }

        $editForm = $this->createEditForm($attendance, $courseid, $rollid, $user);
        $deleteForm = $this->createDeleteForm($id, $courseid, $rollid, $user);

        return array(
            'attendance'      => $attendance,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Attendance entity.
    *
    * @param Attendance $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($attendance, $courseid, $rollid, $user)
    {
        $form = $this->createForm(new AttendanceType(), $attendance, array(
            'action' => $this->generateUrl('attendance_update', array('id' => $attendance->getId(),'courseid'=>$courseid,'rollid'=>$rollid, 'user'=>$user)),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Update','attr' => array('class' => 'btn btn-primary pull-right')));

        return $form;
    }


    /**
     * Edits an existing Attendance entity.
     *
     * @Route("/{courseid}/{rollid}/{user}/{id}", name="attendance_update")
     * @Method("PUT")
     * @Template("MarcaGradebookBundle:Attendance:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $courseid, $rollid, $user)
    {
        $em = $this->getEm();

        $attendance = $em->getRepository('MarcaGradebookBundle:Attendance')->find($id);

        if (!$attendance) {
            throw $this->createNotFoundException('Unable to find Attendance entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $courseid, $rollid, $user);
        $editForm = $this->createEditForm($attendance, $courseid, $rollid, $user);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('course_roll_profile', array('courseid' => $courseid,'rollid' => $rollid, 'user' => $user)));
        }

        return array(
            'entity'      => $attendance,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Attendance entity.
     *
     * @Route("/{courseid}/{rollid}/{user}/{id}/delete", name="attendance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id, $courseid, $rollid, $user)
    {
        $form = $this->createDeleteForm($id, $courseid, $rollid, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $entity = $em->getRepository('MarcaGradebookBundle:Attendance')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Attendance entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('course_roll_profile', array('courseid' => $courseid,'rollid' => $rollid, 'user' => $user)));
    }

    /**
     * Creates a form to delete a Attendance entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid, $rollid, $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attendance_delete', array('id' => $id,'courseid' => $courseid,'rollid' => $rollid, 'user' => $user)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete','attr' => array('class' => 'btn btn-default')))
            ->getForm()
        ;
    }
}
