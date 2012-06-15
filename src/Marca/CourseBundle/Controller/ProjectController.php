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

        $entities = $em->getRepository('MarcaCourseBundle:Project')->findAll();

        return array('entities' => $entities);
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

        $entity = $em->getRepository('MarcaCourseBundle:Project')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/{courseid}/new", name="project_new")
     * @Template()
     */
    public function newAction($courseid)
    {
        $em = $this->getEm();
        
        //find current # of projects in course (so we can suggest setting sortOrder to this +1
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $maxCourse = count($course->getProjects());
        $project = new Project();
        $project->setName('New Project');
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
     * @Route("/{courseid}/create", name="project_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Project:new.html.twig")
     */
    public function createAction($courseid)
    {
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project  = new Project(); 
        $project->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new ProjectType(), $project);
        $form->bindRequest($request);

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
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/{courseid}/edit", name="project_edit")
     * @Template()
     */
    public function editAction($id,$courseid)
    {
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
     * @Route("/{id}/update", name="project_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Project:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $course = $project->getCourse();
        $courseid = $project->getCourse()->getId();

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        //before we update the entity, store the old sort order
        $oldSort = $project->getSortOrder();
        
        $editForm   = $this->createForm(new ProjectType(), $project);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            if ($oldSort < $project->getSortOrder()){
                foreach($course->getProjects() as $projects){
                    if ($project->getSortOrder() >= $projects->getSortOrder() && $oldSort < $projects->getSortOrder() && $entity->getName() != $projects->getName()){
                        $currentsort = $projects->getSortOrder();
                        //$project->setName("Changed");
                        $project->setSortOrder($currentsort-1);    
                }
            }
            }
            elseif ($oldSort > $project->getSortOrder()){
                foreach($course->getProjects() as $projects){
                    if ($project->getSortOrder() <= $projects->getSortOrder() && $oldSort > $projects->getSortOrder() && $entity->getName() != $projects->getName()){
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
     * @Route("/{id}/delete", name="project_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);
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
     * @Route("/{projectId}/promote", name="project_promote")
     */
    public function promoteAction($projectId){
        $em = $this->getEm();
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($projectId);
        $courseid = $project->getCourse()->getId();
        if($project->getSortOrder() != 1){
            $currentOrder = $project->getSortOrder();
            $previousProject = $em->getRepository('MarcaCourseBundle:Project')->findProjectBySortOrder($courseid, $currentOrder - 1);
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
     * @Route("/{projectId}/demote", name="project_demote")
     */
    public function demoteAction($projectId){
        $em = $this->getEm();
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($projectId);
        $courseid = $project->getCourse()->getId();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
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

