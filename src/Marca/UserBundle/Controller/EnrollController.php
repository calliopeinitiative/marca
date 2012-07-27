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
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('lastname')
            ->getForm();

        return array(
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
       $request = $this->get('request');
       $postData = $request->request->get('form');
       $lastname = $postData['lastname'];
       $em = $this->getEm(); 
       $courses = $em->getRepository('MarcaCourseBundle:Course')->findCourseByLastname($lastname);
       return array(
            'courses' => $courses,
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
       $em = $this->getEm(); 
       $em->getRepository('MarcaCourseBundle:Roll')->enroll($course, $user);
       return $this->redirect($this->generateUrl('user_home'));
    }  
    
    
}
