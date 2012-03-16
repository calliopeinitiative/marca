<?php

namespace Marca\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Enroll controller.
 *
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/home", name="user_home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $username = $this->get('security.context')->getToken()->getUsername();
        $profile = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username);       
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findCoursesByUserId($userid);
        
        return array('profile' => $profile,'courses' => $courses);
    }
}
