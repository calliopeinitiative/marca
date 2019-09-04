<?php

namespace Marca\GradebookBundle\Controller;

use Marca\GradebookBundle\Entity\Grade;
use Marca\GradebookBundle\Form\GradeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Grade controller.
 *
 * @Route("/grade")
 */
class GradeController extends Controller
{

    /**
     * Lists all Grade entities.
     *
     * @Route("/", name="grade")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcaGradebookBundle:Grade')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Grade entity.
     *
     * @Route("/{courseid}/{gradesetid}/{fileid}/{userid}/create", name="grade_create")
     * @Method("POST")
     * @Template("MarcaGradebookBundle:Grade:new.html.twig")
     */
    public function createAction(Request $request, $fileid, $userid, $courseid, $gradesetid)
    {
        $em = $this->getDoctrine()->getManager();
        $grade = new Grade();
        $options = array('gradesetid' => $gradesetid);
        $file = $em->getRepository('MarcaFileBundle:File')->find($fileid);


        $form = $this->createCreateForm($grade, $fileid, $userid, $courseid, $options);
        $form->handleRequest($request);
        // check to see if there is a related file; if not, set user but not file
        if ($file){
            $user = $file->getUser();
            $grade->setFile($file);
            $grade->setUser($user);
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
            $grade->setUser($user);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($grade);
            $em->flush();

            if ($file){
            return $this->redirect($this->generateUrl('grade_show_ajax', array('courseid'=>$courseid, 'gradeid'=>$grade->getId(), 'id' => $grade->getUser()->getId())));
            }
            else {
            return $this->redirect($this->generateUrl('grades_show_ajax', array('courseid'=>$courseid, 'gradeid'=>$grade->getId(), 'id' => $grade->getUser()->getId())));
            }
        }

        return array(
            'grade' => $grade,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Grade entity.
     *
     * @param Grade $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Grade $grade, $fileid, $userid, $courseid, $options)
    {
        $gradesetid = $options['gradesetid'];
        $form = $this->createForm(new GradeType($options), $grade, array(
            'action' => $this->generateUrl('grade_create', array('courseid'=>$courseid,'gradesetid' => $gradesetid,'fileid' => $fileid,'userid' => $userid)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-default'),));

        return $form;
    }

    /**
     * Displays a form to create a new Grade entity.
     *
     * @Route("/{courseid}/{fileid}/{userid}/{value}/new", name="grade_new",defaults={"fileid"="0","userid"="0", "value"="0"})
     * @Method("GET")
     * @Template("MarcaGradebookBundle:Grade:new_modal.html.twig")
     */
    public function newAction($courseid, $fileid, $userid, $value)
    {
        $em = $this->getDoctrine()->getManager();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $grade = new Grade();
        $grade->setGrade($value);
        // check to see if there is a related file; if not, set user but not file
        if ($file){
            $user = $file->getUser();
            $grade->setFile($file);
            $grade->setUser($user);
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
            $grade->setUser($user);
        }

        $gradesetid = $course->getGradeset()->getId();

        $options = array('gradesetid' => $gradesetid);
        $form   = $this->createCreateForm($grade, $fileid, $userid, $courseid, $options);


        return array(
            'grade' => $grade,
            'form'   => $form->createView(),

        );
    }

    /**
     * Finds and displays a Grade entity.
     *
     * @Route("/{courseid}/{gradeid}/{id}/show_ajax", name="grade_show_ajax")
     * @Method("GET")
     * @Template()
     */
    public function show_ajaxAction($gradeid)
    {
        $em = $this->getDoctrine()->getManager();

        $grade = $em->getRepository('MarcaGradebookBundle:Grade')->find($gradeid);

        if (!$grade) {
            throw $this->createNotFoundException('Unable to find Grade entity.');
        }

        return array(
            'grade'      => $grade,
        );
    }

    /**
     * Finds and displays a Grade entity.
     *
     * @Route("/{courseid}/{gradeid}/{id}/grades_show_ajax", name="grades_show_ajax")
     * @Method("GET")
     * @Template()
     */
    public function show_grades_ajaxAction($courseid,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $user = $em->getRepository('MarcaUserBundle:User')->find($id);

        $grades = $em->getRepository('MarcaGradebookBundle:Grade')->findGradesByCourse($user,$course);

        return array(
            'grades'      => $grades,
        );
    }


    /**
     * Displays a form to edit an existing Grade entity.
     *
     * @Route("/{courseid}/{id}/edit", name="grade_edit")
     * @Method("GET")
     * @Template("MarcaGradebookBundle:Grade:edit_modal.html.twig")
     */
    public function editAction($id, $courseid)
    {
        $em = $this->getDoctrine()->getManager();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $gradesetid = $course->getGradeset()->getId();
        $options = array('gradesetid' => $gradesetid);

        $grade = $em->getRepository('MarcaGradebookBundle:Grade')->find($id);

        if (!$grade) {
            throw $this->createNotFoundException('Unable to find Grade entity.');
        }

        $editForm = $this->createEditForm($grade, $courseid, $options);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        return array(
            'grade'      => $grade,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Grade entity.
    *
    * @param Grade $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Grade $grade, $courseid, $options)
    {
        $form = $this->createForm(new GradeType($options), $grade, array(
            'action' => $this->generateUrl('grade_update', array('id' => $grade->getId(),'courseid' => $courseid,)),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-default'),));

        return $form;
    }
    /**
     * Edits an existing Grade entity.
     *
     * @Route("/{courseid}/{id}", name="grade_update")
     * @Method("PUT")
     * @Template("MarcaGradebookBundle:Grade:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $courseid)
    {
        $em = $this->getDoctrine()->getManager();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $gradesetid = $course->getGradeset()->getId();
        $options = array('gradesetid' => $gradesetid);

        $grade = $em->getRepository('MarcaGradebookBundle:Grade')->find($id);

        if (!$grade) {
            throw $this->createNotFoundException('Unable to find Grade entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $courseid);
        $editForm = $this->createEditForm($grade, $courseid, $options);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grades_show_ajax', array('courseid'=>$courseid, 'gradeid'=>$grade->getId(), 'id' => $grade->getUser()->getId())));
        }

        return array(
            'grade'      => $grade,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Grade entity.
     *
     * @Route("/{courseid}/{id}/delete", name="grade_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id, $courseid)
    {
        $form = $this->createDeleteForm($id, $courseid);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $grade = $em->getRepository('MarcaGradebookBundle:Grade')->find($id);
            $userid = $grade->getUser()->getId();

            if (!$grade) {
                throw $this->createNotFoundException('Unable to find Grade entity.');
            }

            $em->remove($grade);
            $em->flush();

        }

        return $this->redirect($this->generateUrl('grades_show_ajax', array('courseid'=>$courseid, 'gradeid'=>$id, 'id' => $userid)));
    }

    /**
     * Creates a form to delete a Grade entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grade_delete', array('id' => $id,'courseid' => $courseid,)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-default btn-danger'),))
            ->getForm()
        ;
    }
}
