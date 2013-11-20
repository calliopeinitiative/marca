<?php

namespace Marca\CourseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Project;
use Marca\CourseBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/", name="project")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $projects = $em->getRepository('MarcaCourseBundle:Project')->findAll();

        return array('projects' => $projects);
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{id}/show", name="project_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'project'      => $project,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/{courseid}/{resource}/new", name="project_new")
     * @Template()
     */
    public function newAction($courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        
        //find current # of projects in course (so we can suggest setting sortOrder to this +1
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $maxCourse = count($course->getProjects());
        $project = new Project();
        $project->setResource($resource);
        $project->setSortOrder($maxCourse + 1);
        $form   = $this->createForm(new ProjectType(), $project);

        return array(
            'project' => $project,
            'courseid' => $courseid,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/{courseid}/{resource}/create", name="project_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Project:new.html.twig")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project  = new Project(); 
        $project->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new ProjectType(), $project);
        $form->bind($request);

        if ($form->isValid()) {
            //iterates over current courses, updates sort orders
            foreach($course->getProjects() as $projects){
                if ($project->getSortOrder() <= $projects->getSortOrder()){
                    $currentsort = $projects->getSortOrder();
                    $projects->setSortOrder($currentsort+1);    
                }
            }
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
            
        }

        return array(
            'project' => $project,
            'courseid' => $courseid,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/{courseid}/edit", name="project_edit")
     * @Template()
     */
    public function editAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        
        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        
        $editForm = $this->createForm(new ProjectType(), $project);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'project'      => $project,
            'courseid'      => $courseid,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Project entity.
     *
     * @Route("/{id}/{courseid}/update", name="project_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Project:edit.html.twig")
     */
    public function updateAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $course = $project->getCourse();
        $courseid = $course->getId();

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        //before we update the entity, store the old sort order
        $oldSort = $project->getSortOrder();
        
        $editForm   = $this->createForm(new ProjectType(), $project);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            if ($oldSort < $project->getSortOrder()){
                foreach($course->getProjects() as $projects){
                    if ($project->getSortOrder() >= $projects->getSortOrder() && $oldSort < $projects->getSortOrder() && $project->getName() != $projects->getName()){
                        $currentsort = $projects->getSortOrder();
                        //$project->setName("Changed");
                        $project->setSortOrder($currentsort-1);    
                }
            }
            }
            elseif ($oldSort > $project->getSortOrder()){
                foreach($course->getProjects() as $projects){
                    if ($project->getSortOrder() <= $projects->getSortOrder() && $oldSort > $projects->getSortOrder() && $project->getName() != $projects->getName()){
                        $currentsort = $projects->getSortOrder();
                        //$project->setName("Changed");
                        $projects->setSortOrder($currentsort+1);    
                }
            }
            }
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
        }

        return array(
            'project'      => $project,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{courseid}/{id}/delete", name="project_delete")
     * @Method("post")
     */
    public function deleteAction($courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);
        $em = $this->getEm();
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $courseid = $project->getCourse()->getId();
        
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $course->getProjects();
        
     
        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        
        
        $em->remove($project);
        $em->flush();
            
        //}

        return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
    }

    private function createDeleteForm($id)
    {
        //This function is not currently used (it was called from deleteAction)
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

     /**
     * Moves a Project entity up one in the display order.
     *
     * @Route("/{courseid}/{projectId}/promote", name="project_promote")
     */
    public function promoteAction($projectId)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($projectId);
        $course = $project->getCourse();
        $courseid = $course->getId();

        if($project->getSortOrder() != 1){
            $currentOrder = $project->getSortOrder();
            $previousProject = $em->getRepository('MarcaCourseBundle:Project')->findProjectBySortOrder($course, $currentOrder - 1);
            $project->setSortOrder($currentOrder-1);
            $previousProject->setSortOrder($currentOrder);
            $em->persist($project);
            $em->persist($previousProject);
            $em->flush();
        }
        
        return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));

    }
    
     /**
     * Moves a Project entity down one in the display order.
     *
     * @Route("/{courseid}/{projectId}/demote", name="project_demote")
     */
    public function demoteAction($projectId)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($projectId);
        $course = $project->getCourse();
        $courseid = $course->getId();

        if($project->getSortOrder() != count($course->getProjects())){
            $currentOrder = $project->getSortOrder();
            $previousProject = $em->getRepository('MarcaCourseBundle:Project')->findProjectBySortOrder($courseid, $currentOrder + 1);
            $project->setSortOrder($currentOrder+1);
            $previousProject->setSortOrder($currentOrder);
            $em->persist($project);
            $em->persist($previousProject);
            $em->flush();
        }
        
        return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));

    }
}

