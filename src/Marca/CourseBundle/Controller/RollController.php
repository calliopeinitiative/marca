<?php

namespace Marca\CourseBundle\Controller;

use Marca\CourseBundle\Entity\Roll;
use Marca\CourseBundle\Form\RollType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     */
    public function indexAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_STUDENT, self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $course = $this->getCourse($request);
        $roll = $this->getRoll($request);
        $role = $this->getCourseRole($request);

        return $this->render('MarcaCourseBundle:Roll:index.html.twig', array(
            'roll' => $roll,
            'course' => $course,
            'role'=> $role
        ));
    }

    /**
     * Lists Profile
     *
     * @Route("/{courseid}/{rollid}/{user}/course_roll_profile", name="course_roll_profile")
     */
    public function courseRollProfileAction(Request $request, $rollid)
    {
        $allowed = array(self::ROLE_STUDENT, self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $roll_user = $em->getRepository('MarcaCourseBundle:Roll')->findUserByRoll($rollid);
        $user = $em->getRepository('MarcaUserBundle:User')->find($roll_user);
        $course = $this->getCourse($request);
        $grades = $em->getRepository('MarcaGradebookBundle:Grade')->findGradesByCourse($user,$course);
        $roll = $this->getRoll($request);
        $role = $this->getCourseRole($request);
        $profile = $em->getRepository('MarcaCourseBundle:Roll')->findRollUser($rollid);
        $countForums = $em->getRepository('MarcaForumBundle:Forum')->countForumsByUser($user,$course);
        $countComments = $em->getRepository('MarcaForumBundle:Comment')->countCommentsByUser($user,$course);        
        $countReplies = $em->getRepository('MarcaForumBundle:Reply')->countRepliesByUser($user,$course);
        $countCourseForums = $em->getRepository('MarcaForumBundle:Forum')->countForumsByCourse($course);
        $countCourseComments = $em->getRepository('MarcaForumBundle:Comment')->countCommentsByCourse($course);        
        $countCourseReplies = $em->getRepository('MarcaForumBundle:Reply')->countRepliesByCourse($course);
        
        $countJournals = $em->getRepository('MarcaJournalBundle:Journal')->countJournalsByUser($user,$course);
        $countCourseJournals = $em->getRepository('MarcaJournalBundle:Journal')->countJournalsByCourse($course);
        $countFeedback = $em->getRepository('MarcaAssignmentBundle:Review')->countFeedbackByUser($user,$course);
        $countCourseFeedback = $em->getRepository('MarcaAssignmentBundle:Review')->countFeedbackByCourse($course);
        $countFiles = $em->getRepository('MarcaFileBundle:File')->countFilesByUser($user,$course);
        $countCourseFiles = $em->getRepository('MarcaFileBundle:File')->countFilesByCourse($course);
        $countReviews = $em->getRepository('MarcaFileBundle:File')->countReviewsByUser($user,$course);
        $countCourseReviews = $em->getRepository('MarcaFileBundle:File')->countReviewsByCourse($course);

        return $this->render('MarcaCourseBundle:Course:profile.html.twig', array(
            'user'=> $user,
            'role' => $role,
            'roll' => $roll,
            'profile' => $profile,
            'grades' => $grades,
            'course' => $course,
            'countForums'=>$countForums,
            'countComments'=>$countComments,
            'countReplies'=>$countReplies,
            'countCourseForums'=>$countCourseForums,
            'countCourseComments'=>$countCourseComments,
            'countCourseReplies'=>$countCourseReplies,
            'countJournals'=>$countJournals,
            'countCourseJournals'=>$countCourseJournals,
            'countFiles'=>$countFiles,
            'countCourseFiles'=>$countCourseFiles,
            'countReviews'=>$countReviews,
            'countCourseReviews'=>$countCourseReviews,
            'countFeedback'=>$countFeedback,
            'countCourseFeedback'=>$countCourseFeedback,
        ));
    }     


    /**
     * Finds and displays a Roll for deletion.
     *
     * @Route("/{courseid}/{id}/roll_modal", name="user_roll_modal")
     */
    public function showRollModalAction($courseid,$id)
    {
        //NB security is handled in the conditional below

        $em = $this->getEm();
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$roll) {
            throw $this->createNotFoundException('Unable to find Roll roll.');
        }
        if ($roll->getUser() != $user) {
            throw new AccessDeniedException();
        }


        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaCourseBundle:Roll:delete_modal.html.twig', array(
            'roll'      => $roll,
            'delete_form' => $deleteForm->createView(),
            'course' => $course
        ));
    }

    /**
     * Dialog for Instructor delete of roll listing
     *
     * @Route("/{courseid}/{id}/confirm_delete", name="roll_confirm_delete")
     * @Template()
     */
    public function confirmDeleteAction(Request $request, $id)
    {
        $allowed = array(self::ROLE_STUDENT, self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $course = $this->getCourse($request);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$roll) {
            throw $this->createNotFoundException('Unable to find Roll roll.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaCourseBundle:Roll:confirm_delete.html.twig', array(
            'roll'      => $roll,
            'delete_form' => $deleteForm->createView(),
            'course' => $course
        ));
    }


    /**
     * Deletes a Roll roll.
     *
     * @Route("/{courseid}/{id}/delete", name="roll_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($request, $allowed);
        
        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

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
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }


    /**
     * Deletes a pending Roll roll (self delete by student).
     *
     * @Route("/{courseid}/{id}/pending_delete", name="roll_pending_delete")
     * @Method("post")
     */
    public function rollPendingDeleteAction(Request $request, $id)
    {
        //NB security is handled in the conditional below

        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $roll = $em->getRepository('MarcaCourseBundle:Roll')->find($id);
            $user = $this->getUser();

            if (!$roll) {
                throw $this->createNotFoundException('Unable to find Roll entity.');
            }
            if ($roll->getUser() != $user) {
                throw new AccessDeniedException();
            }

            $em->remove($roll);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_home'));
    }



       
    /**
     *Promote a student to TA
     * @Route("/{courseid}/{id}/approve_pending" , name="roll_approve")
     *
     */
     public function approveAction(Request $request, $id, $courseid)
     {
         $allowed = array(self::ROLE_INSTRUCTOR);
         $this->restrictAccessTo($request, $allowed);
        
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
     public function promoteAction(Request $request, $id, $courseid, $role)
     {
         $allowed = array(self::ROLE_INSTRUCTOR);
         $this->restrictAccessTo($request, $allowed);
        
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
     public function approveAllAction(Request $request, $courseid)
     {
         $allowed = array(self::ROLE_INSTRUCTOR);
         $this->restrictAccessTo($request, $allowed);
         $em = $this->getEm();
         $course = $this->getCourse($request);
         foreach($course->getRoll() as $roll){
            if ($roll->getRole() == self::ROLE_PENDING){ 
                $roll->setRole(self::ROLE_STUDENT);
                $em->persist($roll);
            }
         }
         $em->flush();
         return $this->redirect($this->generateUrl('roll', array('courseid' => $courseid)));
         
     }
    
}
