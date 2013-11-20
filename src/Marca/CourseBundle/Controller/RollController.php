<?php

namespace Marca\CourseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\CourseBundle\roll\Roll;
use Marca\CourseBundle\Form\RollType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Roll controller.
 *
 * @Route("/roll")
 */
class RollController extends Controller
{
    /**
     * Lists all Roll entities.
     *
     * @Route("/{courseid}/", name="roll")
     * @Template()
     */
    public function indexAction()
    {
        $course = $this->getCourse();
        $courseRoll = $this->getRoll();
        
        $paginator = $this->get('knp_paginator');
        $roll = $paginator->paginate($courseRoll,$this->get('request')->query->get('page',1),20);
        
        return array('roll' => $roll, 'course' => $course);
    }
    
    
    /**
     * Lists Roll for Course home.
     *
     * @Route("/{courseid}/course_roll", name="course_roll")
     * @Template("MarcaCourseBundle:Course:roll.html.twig")
     */
    public function courseRollAction()
    {
        $allowed = array(self::ROLE_STUDENT, self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $course = $this->getCourse();
        $full_roll = $this->getRoll();
        $paginator = $this->get('knp_paginator');
        $roll = $paginator->paginate($full_roll,$this->get('request')->query->get('page',1),20);
        
        return array('roll' => $roll,'full_roll' => $full_roll, 'course' => $course);
    }
    
    /**
     * Lists Profile
     *
     * @Route("/{courseid}/{rollid}/{user}/course_roll_profile", name="course_roll_profile")
     * @Template("MarcaCourseBundle:Course:profile.html.twig")
     */
    public function courseRollProfileAction($rollid)
    {
        $em = $this->getEm();
        $userid = $em->getRepository('MarcaCourseBundle:Roll')->findUserByRoll($rollid);
        $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        $course = $this->getCourse();
        $roll = $this->getRoll();
        $role = $this->getCourseRole();
        $profile = $em->getRepository('MarcaCourseBundle:Roll')->findRollUser($rollid);
        $countForums = $em->getRepository('MarcaForumBundle:Forum')->countForumsByUser($user,$course);
        $countComments = $em->getRepository('MarcaForumBundle:Comment')->countCommentsByUser($user,$course);        
        $countReplies = $em->getRepository('MarcaForumBundle:Reply')->countRepliesByUser($user,$course);
        $countCourseForums = $em->getRepository('MarcaForumBundle:Forum')->countForumsByCourse($course);
        $countCourseComments = $em->getRepository('MarcaForumBundle:Comment')->countCommentsByCourse($course);        
        $countCourseReplies = $em->getRepository('MarcaForumBundle:Reply')->countRepliesByCourse($course);
        
        $countJournals = $em->getRepository('MarcaJournalBundle:Journal')->countJournalsByUser($user,$course);
        $countCourseJournals = $em->getRepository('MarcaJournalBundle:Journal')->countJournalsByCourse($course);
        $countFiles = $em->getRepository('MarcaFileBundle:File')->countFilesByUser($user,$course);
        $countCourseFiles = $em->getRepository('MarcaFileBundle:File')->countFilesByCourse($course);

        return array('user'=> $user, 'role' => $role, 'roll' => $roll, 'profile' => $profile, 'course' => $course, 'countForums'=>$countForums, 'countComments'=>$countComments, 'countReplies'=>$countReplies, 'countCourseForums'=>$countCourseForums, 'countCourseComments'=>$countCourseComments, 'countCourseReplies'=>$countCourseReplies,'countJournals'=>$countJournals,'countCourseJournals'=>$countCourseJournals,'countFiles'=>$countFiles,'countCourseFiles'=>$countCourseFiles);
    }     

    /**
     * Finds and displays a Roll roll.
     *
     * @Route("/{courseid}/{id}/show", name="roll_show")
     * @Template()
     */
    public function showAction($id)
    {
        $allowed = array(self::ROLE_STUDENT, self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$roll) {
            throw $this->createNotFoundException('Unable to find Roll roll.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'roll'      => $roll,
            'delete_form' => $deleteForm->createView(), 
            'course' => $course,);
    }

    /**
     * Displays a form to create a new Roll roll.
     *
     * @Route("/new", name="roll_new")
     * @Template()
     */
    public function newAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $roll = new Roll();
        $form   = $this->createForm(new RollType(), $roll);

        return array(
            'roll' => $roll,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Roll roll.
     *
     * @Route("/create", name="roll_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Roll:new.html.twig")
     */
    public function createAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $roll  = new Roll();
        $request = $this->getRequest();
        $form    = $this->createForm(new RollType(), $roll);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($roll);
            $em->flush();

            return $this->redirect($this->generateUrl('roll_show', array('id' => $roll->getId())));
            
        }

        return array(
            'roll' => $roll,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Roll roll.
     *
     * @Route("/{courseid}/{id}/edit", name="roll_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$roll) {
            throw $this->createNotFoundException('Unable to find Roll roll.');
        }

        $editForm = $this->createForm(new RollType(), $roll);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'roll'      => $roll,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Roll roll.
     *
     * @Route("/{courseid}/{id}/update", name="roll_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Roll:edit.html.twig")
     */
    public function updateAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$roll) {
            throw $this->createNotFoundException('Unable to find Roll roll.');
        }

        $editForm   = $this->createForm(new RollType(), $roll);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($roll);
            $em->flush();

            return $this->redirect($this->generateUrl('roll', array('courseid' => $courseid)));
        }

        return array(
            'roll'      => $roll,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Roll roll.
     *
     * @Route("/{courseid}/{id}/delete", name="roll_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

            if (!$roll) {
                throw $this->createNotFoundException('Unable to find Roll entity.');
            }

            $em->remove($roll);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('roll', array('courseid' => $courseid)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
       
    /**
     *Promote a student to TA
     * @Route("/{courseid}/{id}/approve_pending" , name="roll_approve")
     *
     */
     public function approveAction($id, $courseid)
     {
         $allowed = array(self::ROLE_INSTRUCTOR);
         $this->restrictAccessTo($allowed);
        
         $em = $this->getEm();
         $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);
         //remove pending student role
         $roll->setRole(self::ROLE_STUDENT);
         $em->persist($roll);
         $em->flush();
         return $this->redirect($this->generateUrl('roll', array('courseid' => $courseid)));
         
     }   
     
    /**
     * 
     * @Route("/{courseid}/{id}/{role}/promote" , name="roll_promote")
     *
     */
     public function promoteAction($id, $courseid, $role)
     {
         $allowed = array(self::ROLE_INSTRUCTOR);
         $this->restrictAccessTo($allowed);
        
         $em = $this->getEm();
         $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);
         if ($role == 1 ){ $newRole= self::ROLE_STUDENT;} elseif ($role == 2) {$newRole= self::ROLE_INSTRUCTOR;} elseif ($role == 4) {$newRole= self::ROLE_PORTREVIEW;}  else {$newRole= self::ROLE_TA;}
         $roll->setRole($newRole);
         $em->persist($roll);
         $em->flush();
         return $this->redirect($this->generateUrl('roll', array('courseid' => $courseid)));
         
     }  
     
     /**
     *Approves a pending student 
     * @Route("/{courseid}/approve_all_pending" , name="roll_approve_all")
     *
     */
     public function approveAllAction($courseid)
     {
         $allowed = array(self::ROLE_INSTRUCTOR);
         $this->restrictAccessTo($allowed);
         $em = $this->getEm();
         $course = $this->getCourse();
         foreach($course->getRoll() as $roll){
            if ($roll->getRole() == self::ROLE_PENDING){ 
                $roll->setRole(self::ROLE_STUDENT);
                $em->persist($roll);
                $em->flush();
            }
         }
         return $this->redirect($this->generateUrl('roll', array('courseid' => $courseid)));
         
     }
    
}
