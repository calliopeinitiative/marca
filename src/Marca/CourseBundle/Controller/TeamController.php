<?php

namespace Marca\CourseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Team;
use Marca\CourseBundle\Form\TeamType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Team Controller
 * 
 * @Route("/team")
 */
class TeamController extends Controller

{
    
    /**
     * Lists all Teams.
     *
     * @Route("/{courseid}/", name="teams")
     * @Template("MarcaCourseBundle:Team:manage.html.twig")
     */
    public function indexAction($courseid)
    {
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $teams = $course->getTeams();
        
        return array('teams' => $teams, 'course' => $course);
    }
    
    
    /**
     * Finds and displays a team
     * @Route("/{id}/show", name="team_show")
     * @Template()
     */
    public function showAction($id)
    {
       $em = $this->getEm();
       
       $team = $em->getRepository('MarcaCourseBundle:Team')->find($id);
       
       if(!$team){
           throw $this->createNotFoundException('Unable to find Team entity.');
       }
    
       return array('team' => $team);
       
    }
    
    /**
     * Displays interface to select and edit teams
     * @Route("/{courseid}/manage", name="team_manage")
     * @Template()
     */
    public function manageAction($courseid)
    {
       $allowed = array(self::ROLE_INSTRUCTOR);
       $this->restrictAccessTo($allowed);
        
       $course = $this->getCourse();
       $teams = $course->getTeams();
       
       return array('teams' => $teams, 
                     'course' => $course);
       
    }
    
    /**
     * Displays form to edit a team
     * @Route("/{courseid}/{id}/edit", name="team_edit")
     * @Template()
     */
    public function editAction($courseid, $id)
    {
       $allowed = array(self::ROLE_INSTRUCTOR);
       $this->restrictAccessTo($allowed);
        
       
       $em = $this->getEm();
       $course = $this->getCourse();
       $team = $em->getRepository('MarcaCourseBundle:Team')->find($id);
       if (!$team){
           throw $this->createNotFoundException('Unable to find Team entity');
       }
       $editForm = $this->createForm(new TeamType(), $team);
       
       return array('team' => $team, 
                    'course' => $course,
                    'courseid' => $courseid,
                    'edit_form'=> $editForm->createView()
               );
       
    }
    
    /**
     * Edits an existing team entity.
     *
     * @Route("/{courseid}/{id}/update", name="team_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Team:edit.html.twig")
     */
    public function updateAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $team = $em->getRepository('MarcaCourseBundle:Team')->find($id);
        $course = $team->getCourse();
        $courseid = $team->getCourse()->getId();

        if (!$team) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        
        $editForm   = $this->createForm(new TeamType(), $team);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            $em->persist($team);
            $em->flush();

            return $this->redirect($this->generateUrl('teams', array('courseid' => $courseid)));
        }
    }

    /**
     * Displays form to create a team
     * @Route("/{courseid}/new", name="new_team")
     * @Template()
     */
    public function newAction($courseid)
    {
       $allowed = array(self::ROLE_INSTRUCTOR);
       $this->restrictAccessTo($allowed);
        
       $em = $this->getEm();
       $course = $this->getCourse();
       $team = new Team();
       $team->setCourse($course);
       $form = $this->createForm(new TeamType(), $team);
       
       return array('team' => $team, 
                    'course' => $course,
                    'courseid' => $courseid,
                    'edit_form'=> $form->createView()
               );
       
    }
    
    /**
     * Creates a new Team entity.
     *
     * @Route("/{courseid}/create", name="create_team")
     * @Method("post")
     * @Template("MarcaCourseBundle:Team:new.html.twig")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $team  = new Team(); 
        $team->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new TeamType(), $team);
        $form->bind($request);

        if ($form->isValid()) {
            
            $em->persist($team);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('courseid' => $courseid)));
            
        }

        return array(
            'team' => $team,
            'form'   => $form->createView()
        );
    }
    
    
    private function createDeleteForm($id)
    {
        //This function is not currently used (it was called from deleteAction)
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

