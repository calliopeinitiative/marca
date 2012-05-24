<?php

namespace Marca\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Course;
use Marca\UserBundle\Entity\Profile;
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
        $entity = new Profile();
        $form = $this->createFormBuilder($entity)
            ->add('lastname')
            ->getForm();

        return array(
            'entity' => $entity,
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
       $em = $this->get('doctrine.orm.entity_manager'); 
       $courses = $em->getRepository('MarcaCourseBundle:Course')->findCourseByLastname($lastname);
       return array(
            'courses' => $courses,
        );
    }     
    
      /**
     * @Route("/{id}/enroll", name="enroll_enroll")
     * @Template()
     */
    public function enrollCourseAction($id)
    {
       $profile_id = $this->get('security.context')->getToken()->getUser()->getProfile()->getId();
       $em = $this->get('doctrine.orm.entity_manager'); 
       $em->getRepository('MarcaCourseBundle:Roll')->enroll($id,$profile_id);
       return $this->redirect($this->generateUrl('user_home'));
    }  
    
    
}
