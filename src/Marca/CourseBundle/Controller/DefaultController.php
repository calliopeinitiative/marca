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
     * @Route("/{id}/home", name="course_home")
     * @Template()
     */
    public function indexAction($id)
    {
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->findOneById($id);
        $session = $this->get('request')->getSession();
        $session->set('courseid', $id);
        
        return array('course' => $course);
    }
}
