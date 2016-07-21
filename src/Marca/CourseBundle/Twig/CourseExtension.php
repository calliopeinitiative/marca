<?php

namespace Marca\CourseBundle\Twig;


/**
 * Description of CourseExtension
 *
 * @author sarasteger revised for 27 by rlbaltha
 */
class CourseExtension extends \Twig_Extension {


    public function __construct($container, $requestStack) {
        $this->container=$container;
        $this->requestStack = $requestStack;
    }

    public function getName() {
        return 'course';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('course_path', array($this, 'course_path'))
        );
    }


    public function course_path($route, array $params=array(), $absolute=false) {

        $request = $this->requestStack->getCurrentRequest();
        $courseid = $request->attributes->get('courseid');
        if (!$courseid) {
            throw new \Exception('No course id in url.');
        }
        $params['courseid'] = $courseid;
        return $this->container->get('router')->generate($route, $params, $absolute);
    }
}

