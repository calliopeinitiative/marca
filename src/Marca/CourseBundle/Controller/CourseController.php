<?php

namespace Marca\CourseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Course;
use Marca\CourseBundle\Entity\Term;
use Marca\CourseBundle\Entity\Roll;
use Marca\UserBundle\Entity\Profile;
use Marca\CourseBundle\Entity\Project;
use Marca\HomeBundle\Entity\Page;
use Marca\CourseBundle\Form\CourseType;
use Marca\CourseBundle\Form\ModuleType;
use Marca\CourseBundle\Form\AnnounceType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Course controller.
 *
 * @Route("/course")
 */
class CourseController extends Controller
{

    /**
     * Creates ESI fragment for course nav
     *
     * @Route("/{courseid}/nav", name="course_nav")
     */
    public function createCoursenavAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        //find default for resources
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, 1);
        $default_resource = $projects[0]->getId();
        $role = $this->getCourseRole();
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findCoursesByUser($user);
        return $this->render('MarcaCourseBundle::coursenav.html.twig', array(
            'course' => $course,
            'courses' => $courses,
            'role'=> $role,
            'user' => $user,
            'default_resource' => $default_resource
        ));
    }


    /**
     * Creates ESI fragment for course roll
     *
     * @Route("/{courseid}/roll", name="course_roll")
     */
    public function createRollAction()
    {
        $roll = $this->getRoll();
        return $this->render('MarcaCourseBundle::roll.html.twig', array(
            'roll' => $roll,
        ));
    }

    /**
     * Create Sidebar fragment
     *
     * @Route("/{courseid}/sidebar", name="course_sidebar")
     */
    public function createSidebarAction($courseid)
    {
        return $this->render('MarcaCourseBundle::sidebar.html.twig', array( ));
    }


    /**
     * Create Delete modal fragment
     *
     * @Route("/{courseid}/delete_modal", name="delete_modal")
     */
    public function createDeletemodalAction($delete_form)
    {
        return $this->render('MarcaCourseBundle::Delete_modal.html.twig', array('delete_form'=>$delete_form ));
    }

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
        return $this->render('MarcaCourseBundle:Course:index.html.twig', array(
            'courses' => $courses
        ));
    }


    /**
     * Landing page once in a course
     * @Route("/{courseid}/home", name="course_home")
     */
    public function homeAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $session = $this->get('session'); 
        $session->clear();
        $username = $user->getFirstname().' '.$user->getLastname();
        $session->set('username', $username);
        
        if ($this->getCourseRole()== Roll::ROLE_PORTREVIEWER){
            return $this->redirect($this->generateUrl('portfolio', array('courseid' => $courseid)));
        };        

        $files = $em->getRepository('MarcaFileBundle:File')->findCoursehomeFiles($course);
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseStart($course);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, 1);
        $default_resource = $projects[0]->getId();

        if ($course->getModule()==1){
            return $this->redirect($this->generateUrl('file_list', array('courseid' => $courseid, 'project'=> $default_resource, 'resource'=> 1,
            'scope'=> 'all')));
        };

        $paginator = $this->get('knp_paginator');
        $calendar = $paginator->paginate($calendar,$this->get('request')->query->get('page', 1),5);
        return $this->render('MarcaCourseBundle:Course:home.html.twig', array(
            'course' => $course,
            'calendar' => $calendar,
            'files'=>$files,
            'default_resource' => $default_resource
        ));
    }    

    /**
     * Finds and displays a Course entity.
     *
     * @Route("/{courseid}/show", name="course_show")
     */
    public function showAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsInSortOrder($course);
        $resources = $em->getRepository('MarcaCourseBundle:Project')->findResourcesInSortOrder($course);
        $parentProjects = $em->getRepository('MarcaCourseBundle:Project')->findParentProjects($course);
        $tagsets = $course->getTagset();
        $portset = $course->getPortset();
        
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $deleteForm = $this->createDeleteForm($courseid);

        return $this->render('MarcaCourseBundle:Course:show.html.twig', array(
            'course'      => $course,
            'projects'    => $projects,
            'resources'    => $resources,
            'parentProjects'    => $parentProjects,            
            'tagsets'     => $tagsets,
            'portset'     => $portset,
            'delete_form' => $deleteForm->createView(),
        ));
    }
  
    
    /**
     * Displays a form to create a new Course entity.
     *
     * @Route("/{type}/new", name="course_new")
     */
    public function newAction($type)
    {
        //type 0 is a new course; type 1 is a module
        $em = $this->getEm();
        $user = $this->getUser();
        $institution = $user->getInstitution();
        //any instructor can create a new course, so give all instructors access
        if (false === $this->get('security.context')->isGranted('ROLE_INSTR')) {
        throw new AccessDeniedException();
        }
        $term = $em->getRepository('MarcaCourseBundle:Term')->findDefault($institution);
        if ($type==0)
        {$name = 'New Course';}
        else {$name = 'New Module';}
        $time = new \DateTime('08:00');

        $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findDefault();
        $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findDefault();
        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->findDefault();  
        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->findDefault(); 
        $term = $em->getRepository('MarcaCourseBundle:Term')->findDefault($institution);
        
        $course = new Course();
        $course->setUser($user);
        $course->setInstitution($institution);
        $course->setName($name);
        $course->setTime($time);
        $course->setPortset($portset); 
        $course->setAssessmentset($assessmentset);  
        $course->setTerm($term);
        if ($type!=0)
        {$course->setEnroll(false);}
        $course->setModule($type);
        foreach ($tagsets as &$tagset) {
        $course->addTagset($tagset);    
        };
        foreach ($markupsets as &$markupset) {
        $course->addMarkupset($markupset);    
        };

        $type= Page::TYPE_COURSE;
        $pages = $em->getRepository('MarcaHomeBundle:Page')->findPageByType($type);
        $options = '';
        $form   = $this->createForm(new CourseType($options), $course);

        return $this->render('MarcaCourseBundle:Course:new.html.twig', array(
            'course' => $course,
            'pages' => $pages,
            'form'   => $form->createView()
        ));
    }
      

    /**
     * Creates a new Course entity.
     *
     * @Route("/create", name="course_create")
     * @Method("post")
     */
    public function createAction()
    {
        
        $em = $this->getEm();
        $user = $this->getUser();
        $institution = $user->getInstitution();
        $options ='';
        $course  = new Course();
        $course->setUser($user);
        $course->setInstitution($institution);
        $request = $this->getRequest();
        $module = $request->request->get('module');
        $form    = $this->createForm(new CourseType($options), $course);
        $form->submit($request);
        
        $roll = new Roll();
        $roll->setRole(Roll::ROLE_INSTRUCTOR);
        $roll->setUser($user);
        $roll->setStatus(1);
        $roll->setCourse($course);
        
        if ($module == 0)
        {
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

        }
        $project6 = new Project();
        $project6->setName('Readings');
        $project6->setSortOrder(6);
        $project6->setResource(true);
        $project6->setCourse($course);
        
        $project7 = new Project();
        $project7->setName('Assignments');
        $project7->setSortOrder(7);
        $project7->setResource(true);
        $project7->setCourse($course);
        $project7->setCoursehome(true);
        
        $project8 = new Project();
        $project8->setName('Resources');
        $project8->setSortOrder(8);
        $project8->setResource(true);
        $project8->setCourse($course);
        
        $course->setProjectDefault($project1);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($course);
            $em->persist($roll);
            if ($module == 0)
            {
            $em->persist($project1);
            $em->persist($project2);
            $em->persist($project3);
            $em->persist($project4);
            }
            $em->persist($project6);
            $em->persist($project7);
            $em->persist($project8);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $course->getId())));
            
        }

        return $this->render('MarcaCourseBundle:Course:new.html.twig', array(
            'course' => $course,
            'form'   => $form->createView()
        ));
    }
 
    
    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Route("/{courseid}/edit", name="course_edit")
     */
    public function editAction($courseid)
    {
        
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }
        //restrict access to the edit function to the course owner
        if($user != $course->getUser()){
            throw new AccessDeniedException();
        }

        $type= Page::TYPE_COURSE;
        $pages = $em->getRepository('MarcaHomeBundle:Page')->findPageByType($type);
        
        $editForm = $this->createForm(new CourseType($options), $course);
        $deleteForm = $this->createDeleteForm($courseid);

        return $this->render('MarcaCourseBundle:Course:edit.html.twig', array(
            'course'      => $course,
            'pages'      => $pages,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    
    /**
     * Edits an existing Course entity.
     *
     * @Route("/{courseid}/update", name="course_update")
     * @Method("post")
     */
    public function updateAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createForm(new CourseType($options), $course);
        $deleteForm = $this->createDeleteForm($courseid);

        $type= Page::TYPE_COURSE;
        $pages = $em->getRepository('MarcaHomeBundle:Page')->findPageByType($type);

        $request = $this->getRequest();

        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($course);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
        }

        return $this->render('MarcaCourseBundle:Course:edit.html.twig', array(
            'course'      => $course,
            'pages'      => $pages,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Course entity.
     *
     * @Route("/{courseid}/delete", name="course_delete")
     * @Method("post")
     */
    public function deleteAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $user = $this->getUser();
        $institution = $user->getInstitution();
        $term = $em->getRepository('MarcaCourseBundle:Term')->findHidden($institution);

        //restrict access to the delete function to the course owner
        if($user != $course->getUser()){
            throw new AccessDeniedException();
        }
        $form = $this->createDeleteForm($courseid);
        $request = $this->getRequest();
    
        
        $form->submit($request);

        if ($form->isValid()) {
            

            if (!$course) {
                throw $this->createNotFoundException('Unable to find Course entity.');
            }

            $course->setTerm($term);
            $em->persist($course);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_home'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    /**
     * Displays a form to edit an course announcements.
     *
     * @Route("/{courseid}/announce_edit", name="announce_edit")
     */
    public function editAnnouncementAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        
        $em = $this->getEm();
        $user = $this->getUser();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }
        //restrict access to the edit function to the course owner
        if($user != $course->getUser()){
            throw new AccessDeniedException();
        }

        $editForm = $this->createEditForm($course, $options);

        return $this->render('MarcaCourseBundle:Course:announce_edit.html.twig', array(
            'course'      => $course,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a course announcement.
     *
     * @param Course $course
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Course $course, $options)
    {
        $form = $this->createForm(new AnnounceType($options), $course, array(
            'action' => $this->generateUrl('announce_update', array('courseid' => $course->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }
    
    /**
     * Edits an existing Course entity.
     *
     * @Route("/{courseid}/annouce_update", name="announce_update")
     * @Method("post")
     */
    public function updateAnnouncementAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createEditForm($course, $options);

        $request = $this->getRequest();

        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($course);
            $em->flush();

            return $this->redirect($this->generateUrl('course_home', array('courseid' => $courseid)));
        }

        return $this->render('MarcaCourseBundle:Course:announce_edit.html.twig', array(
            'course'      => $course,
            'edit_form'   => $editForm->createView(),
        ));
    }    
    
    
    /**
     * Edits an existing Course entity.
     *
     * @Route("/{courseid}/{setting}/toggle_module", name="toggle_module")
     */
    public function toggleModuleAction($courseid, $setting)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $module_setting = $course->getModule();
        if($module_setting != $setting){
            $course->setModule($setting);
            $em->persist($course);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));

    }    
}
