<?php

namespace Marca\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Enroll controller.
 *
 * @Route("/course")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{id}/home", name="course_home")
     * @Template()
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $username = $this->get('security.context')->getToken()->getUsername();
        $course = $em->getRepository('MarcaCourseBundle:Course')->findOneById($id);
        
        return array('course' => $course);
    }
}
