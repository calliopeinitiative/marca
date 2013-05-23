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
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($current_user);
        $pending = $em->getRepository('MarcaCourseBundle:Course')->findPendingCourses($current_user);
 
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('lastname')
            ->getForm();

        return array(
            'courses'=>$courses,
            'pending'=>$pending,
            'user' => $user,
            'form'   => $form->createView()
        );
    }  
    
    /**
     * @Route("/list", name="enroll_list")
     * @Template()
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
            ->add('lastname')
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
       /*if($this->container->getParameter('pay_to_enroll') && !$user->getCustomer_id())
       {
            return $this->redirect($this->generateUrl('payment', array('courseid'=>$courseid)));
       }
       else
       {*/
            $course = $this->getCourse();
            $em = $this->getEm(); 
            $em->getRepository('MarcaCourseBundle:Roll')->enroll($course, $user);
            return $this->redirect($this->generateUrl('user_home'));
       //}
    }  
    
    
}
