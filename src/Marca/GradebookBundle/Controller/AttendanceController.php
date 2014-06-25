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
     * @Route("/", name="attendance_create")
     * @Method("POST")
     * @Template("MarcaGradebookBundle:Attendance:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Attendance();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('attendance_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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

        return $this->redirect($this->generateUrl('attendance_show_ajax', array('id' => $attendance->getId())));

    }


    /**
    * Creates a form to create a Attendance entity.
    *
    * @param Attendance $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Attendance $entity)
    {
        $form = $this->createForm(new AttendanceType(), $entity, array(
            'action' => $this->generateUrl('attendance_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Attendance entity.
     *
     * @Route("/new", name="attendance_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Attendance();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Attendance entity.
     *
     * @Route("/{id}", name="attendance_show_ajax")
     * @Method("GET")
     * @Template("MarcaGradebookBundle:Attendance:show_ajax.html.twig")
     */
    public function showAjaxAction($id)
    {
        $em = $this->getEm();

        $attendance = $em->getRepository('MarcaGradebookBundle:Attendance')->find($id);

        if (!$attendance) {
            throw $this->createNotFoundException('Unable to find Attendance entity.');
        }

        return array(
            'attendance'      => $attendance,
        );
    }

    /**
     * Displays a form to edit an existing Attendance entity.
     *
     * @Route("/{id}/edit", name="attendance_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $entity = $em->getRepository('MarcaGradebookBundle:Attendance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attendance entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
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
    private function createEditForm(Attendance $entity)
    {
        $form = $this->createForm(new AttendanceType(), $entity, array(
            'action' => $this->generateUrl('attendance_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Attendance entity.
     *
     * @Route("/{id}", name="attendance_update")
     * @Method("PUT")
     * @Template("MarcaGradebookBundle:Attendance:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getEm();

        $entity = $em->getRepository('MarcaGradebookBundle:Attendance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attendance entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('attendance_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Attendance entity.
     *
     * @Route("/{id}", name="attendance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
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

        return $this->redirect($this->generateUrl('attendance'));
    }

    /**
     * Creates a form to delete a Attendance entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attendance_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
