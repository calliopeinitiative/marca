<?php

namespace Marca\UserBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Course;
use Marca\UserBundle\Entity\User;
use Marca\CourseBundle\Entity\Roll;
use Marca\UserBundle\Form\ProfileType;

/**
 * Enroll controller.
 *
 * @Route("/enroll")
 */
class EnrollController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
    }
    
    /**
     * @Route("/find", name="enroll_find")
     * @Template()
     */
    public function findCourseAction()
    {
        $em = $this->getEm();
        $current_user = $this->getUser();
        $id = $current_user->getId();
        if ($current_user->getInstitution()->getResearch()==true and $current_user->getResearch()==0){
        return $this->redirect($this->generateUrl('user_research', array('id' => $id)));
        }
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($current_user);
        $pending = $em->getRepository('MarcaCourseBundle:Course')->findPendingCourses($current_user);
        $courseids = $em->getRepository('MarcaCourseBundle:Course')->findUserCourseIds($current_user);

        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('lastname','text', array('label'  => 'Last name','attr' => array('class' => 'text form-control'),))
            ->getForm();

        $possible_courses = '';

        return array(
            'courses'=>$courses,
            'pending'=>$pending,
            'user' => $user,
            'courseids'=>$courseids,
            'possible_courses' => $possible_courses,
            'form'   => $form->createView()
        );
    }  
    
    /**
     * @Route("/list", name="enroll_list")
     * @Template("MarcaUserBundle:Enroll:findCourse.html.twig")
     */
    public function listCourseAction()
    {
        $em = $this->getEm();
        $current_user = $this->getUser();
        $current_user_id = $current_user->getId();
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($current_user);
        $pending = $em->getRepository('MarcaCourseBundle:Course')->findPendingCourses($current_user);
        $courseids = $em->getRepository('MarcaCourseBundle:Course')->findUserCourseIds($current_user);
        
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('lastname','text', array('label'  => 'Last name','attr' => array('class' => 'text form-control'),))
            ->getForm();
        
       $request = $this->get('request');
       $postData = $request->request->get('form');
       $lastname = $postData['lastname'];
       $em = $this->getEm(); 
       $possible_courses = $em->getRepository('MarcaCourseBundle:Course')->findCourseByLastname($lastname);
       
        
        
       return array(
           'courses'=>$courses,
           'pending'=>$pending,
           'courseids'=>$courseids,
           'possible_courses' => $possible_courses,
           'form'   => $form->createView(),
        );
    }     
    
      /**
     * @Route("/{courseid}/enroll", name="enroll_enroll")
     * @Template()
     */
    public function enrollCourseAction($courseid)
    {
       $user = $this->getUser();
       $course = $this->getCourse();
       $coupon = $user->getCoupon();
       if($this->get('security.context')->isGranted('ROLE_INSTR') === TRUE){
           $validCoupon = TRUE;
       }
       elseif($coupon == NULL){
           $validCoupon = FALSE;
       }
       elseif ($coupon->getTerm()->getStatus() == 1){
           $validCoupon = TRUE;
       }
       else {
           $validCoupon = FALSE;
       }
       if(($course->getInstitution()->getPaymentType() == 1 && $validCoupon == FALSE) || ($course->getInstitution()->getPaymentType() == 2 && $validCoupon == FALSE))
       {
            return $this->redirect($this->generateUrl('payment', array('courseid'=>$courseid)));
       }
       else
            $em = $this->getEm(); 
            $em->getRepository('MarcaCourseBundle:Roll')->enroll($course, $user);
            return $this->redirect($this->generateUrl('user_home'));
    }  
    
    
}
