<?php

namespace Marca\CourseBundle\Twig;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseExtension
 *
 * @author sarasteger
 */
class CourseExtension extends \Twig_Extension {
    
    private $container;
    
    public function __construct($container) {
        $this->container=$container;
    } 
    
    public function getName() {
        return 'course';
    } 
    
    public function getFunctions()
    {
        return array(
            'course_path' => new \Twig_Function_Method($this,'getCoursePath'),
        );
    }
    
    public function getCoursePath($route, array $params=array()) {
        return $this->container->get('router')->generate($route, $parameters, $absolute);
    }
}

