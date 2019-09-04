<?php

namespace Marca\CourseBundle\Controller;

use Marca\CourseBundle\Entity\Project;
use Marca\CourseBundle\Form\ProjectType;
use Marca\CourseBundle\Form\ResourceType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
            'project' => $project,
            'delete_form' => $deleteForm->createView(),);
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/{courseid}/{resource}/new", name="project_new")
     * @Template()
     */
    public function newAction(Request $request, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();

        //find current # of projects in course (so we can suggest setting sortOrder to this +1
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $maxCourse = count($course->getProjects());
        $project = new Project();
        $project->setResource($resource);
        $project->setSortOrder($maxCourse + 1);
        $form = $this->createForm(new ProjectType(), $project);

        return array(
            'project' => $project,
            'courseid' => $courseid,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/{courseid}/{resource}/create", name="project_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Project:new.html.twig")
     */
    public function createAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project = new Project();
        $project->setCourse($course);
        $request = $this->getRequest();
        $form = $this->createForm(new ProjectType(), $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //iterates over current courses, updates sort orders
            foreach ($course->getProjects() as $projects) {
                if ($project->getSortOrder() <= $projects->getSortOrder()) {
                    $currentsort = $projects->getSortOrder();
                    $projects->setSortOrder($currentsort + 1);
                }
            }
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));

        }

        return array(
            'project' => $project,
            'courseid' => $courseid,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/{courseid}/edit", name="project_edit")
     * @Template()
     */
    public function editAction(Request $request, $id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $user = $this->getUser();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $files = $em->getRepository('MarcaFileBundle:File')->checkProjectFiles($project, $user);

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        if ($project->getResource()==0) {
            $editForm = $this->createForm(new ProjectType(), $project);
        }
        else {
            $editForm = $this->createForm(new ResourceType(), $project);
        }


        $deleteForm = $this->createDeleteForm($id);

        return array(
            'project' => $project,
            'files' => $files,
            'courseid' => $courseid,
            'edit_form' => $editForm->createView(),
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
    public function updateAction(Request $request, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $course = $project->getCourse();
        $courseid = $course->getId();

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }



        $editForm = $this->createForm(new ProjectType(), $project);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();
        $editForm->handleRequest($request);
        
        if ($project->getResource()==0) {
            $project->setCoursehome('false');
        }
        if ($editForm->isValid()) {
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
        }

        return array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{courseid}/{id}/delete", name="project_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);
        $em = $this->getEm();
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($id);
        $courseid = $project->getCourse()->getId();

        if (!$project) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $em->remove($project);
        $em->flush();

        return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
    }

    private function createDeleteForm($id)
    {
        //This function is not currently used (it was called from deleteAction)
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    /**
     * Moves a Project entity up one in the display order.
     *
     * @Route("/{courseid}/{projectId}/{previousProjectId}/promote", name="project_promote")
     */
    public function promoteAction(Request $request, $projectId, $previousProjectId)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($projectId);
        $currentOrder = $project->getSortOrder();
        $previousProject = $em->getRepository('MarcaCourseBundle:Project')->find($previousProjectId);
        $previousOrder = $previousProject->getSortOrder();
        $project->setSortOrder($previousOrder);
        $previousProject->setSortOrder($currentOrder);
        $em->persist($project);
        $em->persist($previousProject);
        $em->flush();

        $course = $project->getCourse();
        $courseid = $course->getId();

        return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));

    }

    /**
     * Moves a Project entity down one in the display order.
     *
     * @Route("/{courseid}/{projectId}/{followingProjectId}/demote", name="project_demote")
     */
    public function demoteAction(Request $request, $projectId, $followingProjectId)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();

        $project = $em->getRepository('MarcaCourseBundle:Project')->find($projectId);
        $currentOrder = $project->getSortOrder();
        $followingProject = $em->getRepository('MarcaCourseBundle:Project')->find($followingProjectId);
        $followingOrder = $followingProject->getSortOrder();
        $project->setSortOrder($followingOrder);
        $followingProject->setSortOrder($currentOrder);
        $em->persist($project);
        $em->persist($followingProject);
        $em->flush();

        $course = $project->getCourse();
        $courseid = $course->getId();

        return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));

    }


    /**
     * Set Project Default
     *
     * @Route("/{courseid}/{projectId}/set_default", name="project_set_default")
     */
    public function setDefaultAction(Request $request, $projectId, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($projectId);
        $course = $project->getCourse();
        $course->setProjectDefault($project);
        $em->persist($course);
        $em->flush();

        return $this->redirect($this->generateUrl('file_list', array('courseid' => $courseid, 'project' => 'default', 'scope' => 'mine', 'user' => '0', 'resource' => '0', 'tag' => '0', 'userid' => '0')));

    }
}

