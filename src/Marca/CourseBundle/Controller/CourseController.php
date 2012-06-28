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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


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
     * Landing page once in a course
     * @Route("/{courseid}/home", name="course_home")
     * @Template()
     */
    public function homeAction($courseid)
    {
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->findOneById($courseid);
        $session = $this->get('request')->getSession();
        $session->set('courseid', $courseid);
        
        return array('course' => $course);
    }    

    /**
     * Finds and displays a Course entity.
     *
     * @Route("/{courseid}/show", name="course_show")
     * @Template()
     */
    public function showAction($courseid)
    {
        $allowed = array("instructor");
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $course->getProjectsInSortOrder();
        $tagsets = $course->getTagset();
        $portset = $course->getPortset();
        $roll = $course->getRoll();

        
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $deleteForm = $this->createDeleteForm($courseid);

        return array(
            'course'      => $course,
            'projects'    => $projects,
            'tagsets'    => $tagsets,
            'portset'    => $portset,
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
        //any instructor can create a new course, so give all instructors access
        if (false === $this->get('security.context')->isGranted('ROLE_INSTR')) {
        throw new AccessDeniedException();
        }
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
            'course' => $course,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Route("/{id}/edit", name="course_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $options = array('courseid' => $id);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($id);
        
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }
        //restrict access to the edit function to the course owner
        if($user != $course->getUser()){
            throw new AccessDeniedException();
        }
        
        $editForm = $this->createForm(new CourseType($options), $course);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'course'      => $course,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    
    /**
     * Edits an existing Course entity.
     *
     * @Route("/{id}/update", name="course_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Course:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();
        $options = array('courseid' => $id);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($id);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createForm(new CourseType($options), $course);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($course);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $id)));
        }

        return array(
            'course'      => $course,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Course entity.
     *
     * @Route("/{id}/delete", name="course_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($id);
        $user = $this->getUser();
        //restrict access to the delete function to the course owner
        if($user != $course->getUser()){
            throw new AccessDeniedException();
        }
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();
    
        
        $form->bindRequest($request);

        if ($form->isValid()) {
            

            if (!$course) {
                throw $this->createNotFoundException('Unable to find Course entity.');
            }

            $em->remove($course);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('course'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
