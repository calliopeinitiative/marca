<?php

namespace Marca\AssignmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AssignmentController
 * @package Marca\AssignmentBundle\Controller
 * @Route("/assignment")
 */
class AssignmentController extends Controller
{
    /**
     * @Route("/{courseid}/show", name="assignment_show")
     * @Template()
     */
    public function showAction()
    {
        $course = $this->getCourse();
        $name = $course->getName();
        return array(
            'name' => $name
            );
    }

}
