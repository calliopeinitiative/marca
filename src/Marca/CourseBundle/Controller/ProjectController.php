<?php

namespace Marca\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $em = $this->getDoctrine()->getEntityManager();

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
        $em = $this->getDoctrine()->getEntityManager();

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
        $em = $this->getDoctrine()->getEntityManager();
        
        //find current # of projects in course (so we can suggest setting sortOrder to this +1
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $maxCourse = count($course->getProjects());
        

        $entity = new Project();
        $entity->setName('New Project');
        $entity->setResource('t');
        $entity->setSortOrder($maxCourse + 1);
        $form   = $this->createForm(new ProjectType(), $entity);

        return array(
            'entity' => $entity,
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
        $em = $this->getDoctrine()->getEntityManager();
        $entity  = new Project();
        
      
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $entity->setCourse($course);
        
        $username = $this->get('security.context')->getToken()->getUsername();
        $profile = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username); 
        $userid = $profile->getId();
        $entity->setUserid($userid);
        
        $request = $this->getRequest();
        $form    = $this->createForm(new ProjectType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            //iterates over current courses, updates sort orders
            foreach($course->getProjects() as $project){
                if ($entity->getSortOrder() <= $project->getSortOrder()){
                    $currentsort = $project->getSortOrder();
                    $project->setSortOrder($currentsort+1);    
                }
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('id' => $courseid)));
            
        }

        return array(
            'entity' => $entity,
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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        
        $editForm = $this->createForm(new ProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $course = $entity->getCourse();
        $courseid = $entity->getCourse()->getId();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        //before we update the entity, store the old sort order
        $oldSort = $entity->getSortOrder();
        
        $editForm   = $this->createForm(new ProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            if ($oldSort < $entity->getSortOrder()){
                foreach($course->getProjects() as $project){
                    if ($entity->getSortOrder() >= $project->getSortOrder() && $oldSort < $project->getSortOrder() && $entity->getName() != $project->getName()){
                        $currentsort = $project->getSortOrder();
                        //$project->setName("Changed");
                        $project->setSortOrder($currentsort-1);    
                }
            }
            }
            elseif ($oldSort > $entity->getSortOrder()){
                foreach($course->getProjects() as $project){
                    if ($entity->getSortOrder() <= $project->getSortOrder() && $oldSort > $project->getSortOrder() && $entity->getName() != $project->getName()){
                        $currentsort = $project->getSortOrder();
                        //$project->setName("Changed");
                        $project->setSortOrder($currentsort+1);    
                }
            }
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('id' => $courseid)));
        }

        return array(
            'entity'      => $entity,
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
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $courseid = $entity->getCourse()->getId();
        
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $course->getProjects();
        
        //check if this is project is it's course's default project
        if($entity === $course->getProjectDefault()){
            throw $this->createNotFoundException('Cannot delete default project');
        }
        
        //check to see if this is the default course and reassign default course if it is
     
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        
        
        $em->remove($entity);
        $em->flush();
            
        //}

        return $this->redirect($this->generateUrl('course_show', array('id' => $courseid)));
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
        $em = $this->getDoctrine()->getEntityManager();
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
        
        return $this->redirect($this->generateUrl('course_show', array('id' => $courseid)));

    }
    
     /**
     * Moves a Project entity down one in the display order.
     *
     * @Route("/{projectId}/demote", name="project_demote")
     */
    public function demoteAction($projectId){
        $em = $this->getDoctrine()->getEntityManager();
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
        
        return $this->redirect($this->generateUrl('course_show', array('id' => $courseid)));

    }
}

