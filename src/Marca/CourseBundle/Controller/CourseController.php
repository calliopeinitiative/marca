<?php

namespace Marca\CourseBundle\Controller;

use Marca\CourseBundle\Entity\Course;
use Marca\CourseBundle\Entity\Project;
use Marca\CourseBundle\Entity\Roll;
use Marca\CourseBundle\Entity\Term;
use Marca\CourseBundle\Form\AccessType;
use Marca\CourseBundle\Form\AnnounceType;
use Marca\CourseBundle\Form\CourseTermType;
use Marca\CourseBundle\Form\CourseType;
use Marca\CourseBundle\Form\ModuleType;
use Marca\CourseBundle\Form\NameType;
use Marca\CourseBundle\Form\OtherType;
use Marca\CourseBundle\Form\PortType;
use Marca\CourseBundle\Form\TimeType;
use Marca\CourseBundle\Form\ToolsType;
use Marca\HomeBundle\Controller\Controller;
use Marca\HomeBundle\Entity\Page;
use Marca\UserBundle\Entity\Profile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
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
    public function createCoursenavAction(Request $request)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse($request);
        //find default for resources
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, 1);
        $default_resource = $projects[0]->getId();
        $role = $this->getCourseRole($request);
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
    public function createRollAction(Request $request)
    {
        $roll = $this->getRoll($request);
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
    public function homeAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse($request);
        $session = $this->get('session'); 
        $session->clear();
        $username = $user->getFirstname().' '.$user->getLastname();
        $session->set('username', $username);
        
        if ($this->getCourseRole($request)== Roll::ROLE_PORTREVIEWER){
            return $this->redirect($this->generateUrl('portfolio', array('courseid' => $courseid)));
        };        

        $files = $em->getRepository('MarcaFileBundle:File')->findCoursehomeFiles($course);
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarforCourseHome($course);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, 1);
        $default_resource = $projects[0]->getId();

        if ($course->getModule()==1){
            return $this->redirect($this->generateUrl('file_listbyproject', array('courseid' => $courseid, 'project'=> $default_resource, 'resource'=> 1,
            'scope'=> 'all')));
        };
        
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
    public function showAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
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
     * @Route("/course_create_modal", name="course_create_modal")
     */
    public function courseCreateModalAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findModules($user);
        if (false === $this->get('security.context')->isGranted('ROLE_INSTR')) {
            throw new AccessDeniedException();
        }
        return $this->render('MarcaUserBundle:Default:course_create_modal.html.twig', array(
            'user' => $user,
            'courses' => $courses
        ));
    }

    /**
     * Creates a new Course entity.
     *
     * @Route("/{courseid}/course_create", name="course_create", defaults={"courseid"=0})
     */
    public function createAction($courseid)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $institution = $user->getInstitution();
        //any instructor can create a new course, so give all instructors access
        if (false === $this->get('security.context')->isGranted('ROLE_INSTR')) {
        throw new AccessDeniedException();
        }
        $moduleid = 0;
        //Get Defaults for course creation
        $name = 'New Course';
        $time = new \DateTime('08:00');

        $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findDefault();
        $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findDefault();
        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->findDefault();  
        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->findDefault();
        $modules = $em->getRepository('MarcaCourseBundle:Course')->findDefaultModules();
        $term = $em->getRepository('MarcaCourseBundle:Term')->findDefault($institution);

        //Create the course object and populate
        $course = new Course();
        $course->setUser($user);
        $course->setInstitution($institution);
        $course->setName($name);
        $course->setTime($time);
        $course->setPortset($portset); 
        $course->setAssessmentset($assessmentset);  
        $course->setTerm($term);
        $course->setModule(0);
        foreach ($tagsets as &$tagset) {
        $course->addTagset($tagset);    
        };
        foreach ($markupsets as &$markupset) {
        $course->addMarkupset($markupset);    
        };
        foreach ($modules as &$module) {
            $course->addParent($module);
        };
        $this->addFlash(
        'notice',
        'Welcome to your new course! To customize your course click "Course Settings."'
        );
        // if $courseid = 1 create a new module with the same name as the course
        if($courseid == 1){
            $name = 'New Module';
            $time = new \DateTime('08:00');

            $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findDefault();
            $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findDefault();
            $term = $em->getRepository('MarcaCourseBundle:Term')->findDefault($institution);

            $module = new Course();
            $module->setUser($user);
            $module->setInstitution($institution);
            $module->setName($name);
            $module->setTime($time);
            $module->setTerm($term);
            $module->setModule(1);
            $module->setEnroll(0);
            foreach ($tagsets as &$tagset) {
                $module->addTagset($tagset);
            };
            foreach ($markupsets as &$markupset) {
                $module->addMarkupset($markupset);
            };

            $module_roll = new Roll();
            $module_roll->setRole(Roll::ROLE_INSTRUCTOR);
            $module_roll->setUser($user);
            $module_roll->setStatus(1);
            $module_roll->setCourse($module);

            $module_project6 = new Project();
            $module_project6->setName('Readings');
            $module_project6->setSortOrder(6);
            $module_project6->setResource(true);
            $module_project6->setCourse($module);

            $module_project7 = new Project();
            $module_project7->setName('Assignments');
            $module_project7->setSortOrder(7);
            $module_project7->setResource(true);
            $module_project7->setCourse($module);
            $module_project7->setCoursehome(true);

            $module_project8 = new Project();
            $module_project8->setName('Resources');
            $module_project8->setSortOrder(8);
            $module_project8->setResource(true);
            $module_project8->setCourse($module);

            $module->setProjectDefault($module_project6);
            $em->persist($module);
            $em->persist($module_roll);
            $em->persist($module_project6);
            $em->persist($module_project7);
            $em->persist($module_project8);
            $em->flush();
            $moduleid=$module->getId();
            $course->addParent($module);
            
            $this->addFlash(
            'notice',
            'You will find your new module on the Emma homepage. Resources and calendar events for this course will be drawn from the module you just created. Add and edit content and calendar events within the module.'
        );


        }
        //if $courseid does not = 0 find appropriate module to associate with this course
        elseif ($courseid !=0)
        {
            $module = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
            $course->addParent($module);

            $this->addFlash(
            'notice',
            'Resources and calendar events for this course will be drawn from the module you selected. Add and edit content and calendar events within the module.'
        );
         }


        //Create Roll object and populate with current user
        $roll = new Roll();
        $roll->setRole(Roll::ROLE_INSTRUCTOR);
        $roll->setUser($user);
        $roll->setStatus(1);
        $roll->setCourse($course);

        //Basic project set for course
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

        //Create default resources
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

        //persist all thingys and flush
            $em->persist($course);
            $em->persist($roll);
            $em->persist($project1);
            $em->persist($project2);
            $em->persist($project3);
            $em->persist($project4);
            if ($courseid == 0 ) {
                $em->persist($project6);
                $em->persist($project7);
                $em->persist($project8);
            }
            $em->flush();

            return $this->redirect($this->generateUrl('course_edit', array('courseid' => $course->getId(),'formtype' => 'NameType','moduleid' => $moduleid)));
    }


    /**
     * Creates a new Course entity. This entity functions as a module
     *
     * @Route("/module_create", name="module_create")
     */
    public function createModuleAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $institution = $user->getInstitution();
        //any instructor can create a new course, so give all instructors access
        if (false === $this->get('security.context')->isGranted('ROLE_INSTR')) {
            throw new AccessDeniedException();
        }

        $name = 'New Module';
        $time = new \DateTime('08:00');

        $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findDefault();
        $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findDefault();
        $term = $em->getRepository('MarcaCourseBundle:Term')->findDefault($institution);

        $course = new Course();
        $course->setUser($user);
        $course->setInstitution($institution);
        $course->setName($name);
        $course->setTime($time);
        $course->setTerm($term);
        $course->setModule(1);
        $course->setEnroll(0);
        foreach ($tagsets as &$tagset) {
            $course->addTagset($tagset);
        };
        foreach ($markupsets as &$markupset) {
            $course->addMarkupset($markupset);
        };

        $roll = new Roll();
        $roll->setRole(Roll::ROLE_INSTRUCTOR);
        $roll->setUser($user);
        $roll->setStatus(1);
        $roll->setCourse($course);

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

        $course->setProjectDefault($project6);
        $em->persist($course);
        $em->persist($roll);
        $em->persist($project6);
        $em->persist($project7);
        $em->persist($project8);
        $em->flush();

        return $this->redirect($this->generateUrl('course_edit', array('courseid' => $course->getId(), 'formtype' => 'ModuleType')));
    }


    
    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Route("/{courseid}/{formtype}/{moduleid}/edit", name="course_edit", defaults={"moduleid"=0})
     */
    public function editAction(Request $request, $courseid, $formtype, $moduleid)
    {
        
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
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
               
        $editForm = $this->createEditPartForm($course, $formtype, $moduleid);
        $deleteForm = $this->createDeleteForm($courseid);

        return $this->render('MarcaCourseBundle:Course:edit.html.twig', array(
            'course'      => $course,
            'pages'      => $pages,
            'formtype'   => $formtype,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'moduleid'   => $moduleid,
        ));
    }
    
    /**
     * Creates a form to edit a CoursePart entity.
     *
     * @param Course $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditPartForm(Course $course, $formtype, $moduleid) {
        
        if ($formtype == 'CourseType') {
            $form = $this->createForm(new CourseType(), $course, array(
            'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
            'method' => 'POST', 
        ));
            
        }
        elseif($formtype == 'NameType'){
            $form = $this->createForm(new NameType(), $course, array(
            'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
            'method' => 'POST',
        ));
        }
         elseif($formtype == 'CourseTermType'){
            $form = $this->createForm(new CourseTermType(), $course, array(
            'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
            'method' => 'POST',
        ));
        }
        
        elseif($formtype == 'TimeType'){
            $form = $this->createForm(new TimeType(), $course, array(
            'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
            'method' => 'POST',
        ));
        
            
        }
        elseif($formtype == 'ToolsType'){
            $form = $this->createForm(new ToolsType(), $course, array(
            'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
            'method' => 'POST',
        ));
       
        }
         elseif($formtype == 'AccessType'){
            $form = $this->createForm(new AccessType(), $course, array(
            'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
            'method' => 'POST',
        ));
        }
        elseif($formtype == 'PortType'){
            $form = $this->createForm(new PortType(), $course, array(
            'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
            'method' => 'POST',
        ));
        }
        elseif($formtype == 'ModuleType'){
            $form = $this->createForm(new ModuleType(), $course, array(
                'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
                'method' => 'POST',
            ));
        }
        elseif($formtype == 'OtherType'){
            $form = $this->createForm(new OtherType(), $course, array(
                'action' => $this->generateUrl('course_update', array('courseid' => $course->getId(),'id' => $course->getId(), 'formtype' => $formtype, 'moduleid' => $moduleid)),
                'method' => 'POST',
            ));
        }

         $form->add('submit', 'submit', array('label' => 'Post', 'attr' => array('class' => 'btn btn-primary pull-right'),));

        return $form;
    }


    /**
     * Edits an existing Course entity.
     *
     * @Route("/{courseid}/{formtype}/{moduleid}/update", name="course_update")
     * @Method("post")
     */
    public function updateAction(Request $request, $courseid, $formtype, $moduleid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }
        
        $editForm = $this->createEditPartForm($course, $formtype, $moduleid);

        $deleteForm = $this->createDeleteForm($courseid);

        $type= Page::TYPE_COURSE;
        $pages = $em->getRepository('MarcaHomeBundle:Page')->findPageByType($type);

         $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($course);
            if($moduleid != 0){
                $module = $em->getRepository('MarcaCourseBundle:Course')->find($moduleid);
                $coursename = $course->getName();
                $module->setName($coursename);
                $em->persist($module);


            }
            $em->flush();


            return $this->redirect($this->generateUrl('course_home', array('courseid' => $courseid)));
        }

        return $this->render('MarcaCourseBundle:Course:edit.html.twig', array(
            'course'      => $course,
            'pages'      => $pages,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * "Deletes" a course entity by changing the term to hidden.
     *
     * @Route("/{courseid}/delete", name="course_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $user = $this->getUser();
        $institution = $user->getInstitution();
        //Finds the Hidden Term
        $term = $em->getRepository('MarcaCourseBundle:Term')->findHidden($institution);

        //restrict access to the delete function to the course owner
        if($user != $course->getUser()){
            throw new AccessDeniedException();
        }
        $form = $this->createDeleteForm($courseid);

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
    public function editAnnouncementAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
        
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
        $form = $this->createForm(AnnounceType::class, $course, [
            'action' => $this->generateUrl('announce_update', ['courseid' => $course->getId()])]);
        return $form;
    }



    /**
     * Edits an existing Course entity.
     *
     * @Route("/{courseid}/annouce_update", name="announce_update")
     * @Method("post")
     */
    public function updateAnnouncementAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createEditForm($course, $options);

        $editForm->handleRequest($request);

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
    public function toggleModuleAction(Request $request, $courseid, $setting)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
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
