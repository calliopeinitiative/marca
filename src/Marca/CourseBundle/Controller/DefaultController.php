<?php

namespace Marca\CourseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
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
     * @Route("/{courseid}/home", name="course_home")
     * @Template()
     */
    public function indexAction($courseid)
    {
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->findOneById($courseid);
        $session = $this->get('request')->getSession();
        $session->set('courseid', $courseid);
        
        return array('course' => $course);
    }
}
