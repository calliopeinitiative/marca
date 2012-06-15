<?php

namespace Marca\CourseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Course;
use Marca\CourseBundle\Entity\Roll;
use Marca\UserBundle\Entity\Profile;
use Marca\CourseBundle\Entity\Project;
use Marca\CourseBundle\Form\CourseType;

/**
 * Course controller.
 *
 * @Route("/course")
 */
class CourseController extends Controller
{
    /**
     * Lists all Course entities.
     *
     * @Route("/", name="course")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm(); 
        $user = $this->getUser();
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findCoursesByUser($user);
        return array('courses' => $courses);
    }

    /**
     * Finds and displays a Course entity.
     *
     * @Route("/{courseid}/show", name="course_show")
     * @Template()
     */
    public function showAction($courseid)
    {
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $course->getProjectsInSortOrder();
        $tagsets = $course->getTagset(); 
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $deleteForm = $this->createDeleteForm($courseid);

        return array(
            'course'      => $course,
            'projects'    => $projects,
            'tagsets'    => $tagsets,
            'roll'        => $roll, 
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Course entity.
     *
     * @Route("/new", name="course_new")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->getUser();
        $course = new Course();
        $course->setUser($user);
        
        $form   = $this->createForm(new CourseType($options), $course);

        return array(
            'course' => $course,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Course entity.
     *
     * @Route("/create", name="course_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Course:new.html.twig")
     */
    public function createAction()
    {   $em = $this->getEm();
        $user = $this->getUser();
               
        $course  = new Course();
        $course->setUser($user);
        $request = $this->getRequest();
        $form    = $this->createForm(new CourseType($options), $course);
        $form->bindRequest($request);
        
        $roll = new Roll();
        $roll->setRole(1);
        $roll->setUser($user);
        $roll->setStatus(1);
        $roll->setCourse($course);
        
        $project1 = new Project();
        $project1->setName('Paper 1');
        $project1->setSortOrder(1);
        $project1->setCourse($course);
        
        $project2 = new Project();
        $project2->setName('Paper 2');
        $project2->setSortOrder(2);
        $project2->setCourse($course);
        
        $project3 = new Project();
        $project3->setName('Paper 3');
        $project3->setSortOrder(3);
        $project3->setCourse($course);
        
        $project4 = new Project();
        $project4->setName('Portfolio Prep');
        $project4->setSortOrder(4);
        $project4->setCourse($course); 
        
        $course->setProjectDefault($project1);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($course);
            $em->persist($roll);
            $em->persist($project1);
            $em->persist($project2);
            $em->persist($project3);
            $em->persist($project4);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $course->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Route("/{courseid}/edit", name="course_edit")
     * @Template()
     */
    public function editAction($courseid)
    {
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createForm(new CourseType($options), $course);
        $deleteForm = $this->createDeleteForm($courseid);

        return array(
            'course'      => $course,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    
    /**
     * Edits an existing Course entity.
     *
     * @Route("/{courseid}/update", name="course_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Course:edit.html.twig")
     */
    public function updateAction($courseid)
    {
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createForm(new CourseType($options), $course);
        $deleteForm = $this->createDeleteForm($courseid);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($course);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Course entity.
     *
     * @Route("/{courseid}/delete", name="course_delete")
     * @Method("post")
     */
    public function deleteAction($courseid)
    {
        $form = $this->createDeleteForm($courseid);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

            if (!$course) {
                throw $this->createNotFoundException('Unable to find Course entity.');
            }

            $em->remove($course);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('course'));
    }

    private function createDeleteForm($courseid)
    {
        return $this->createFormBuilder(array('courseid' => $courseid))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
