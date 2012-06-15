<?php

namespace Marca\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Doctrine\ORM\EntityManager;

class Controller extends SymfonyController
{
    /**
     *
     * @return \Marca\UserBundle\Entity\User
     */   
    public function getUser() {
        $user = $this->get('security.context')->getToken()->getUser();
        return $user;
    }
    
    /**
     *
     * @return EntityManager
     */   
    public function getEm() {
        $em = $this->getDoctrine()->getEntityManager();
        return $em;
    }    
    
    /**
     *
     * @return \Marca\CourseBundle\Entity\Course
     */   
    public function getCourse() {
        $request = $this->getRequest();
        $courseid = $request->attributes->get('courseid');
        $course = $this->getEm()->getRepository('MarcaCourseBundle:Course')->find($courseid);
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity');
        }
        return $course;
    } 
    
    /**
     *@return string 
     */
    public function getCourseRole(){
        $request = $this->getRequest();
        $courseid = $request->attributes->get('courseid');
        $course = $this->getEm()->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $user = $this->getUser();
        $rollentry = $this->getEm()->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        if(!$rollentry){
            return "none";
        }
        if($rollentry->getRole() === 1){
            return "instructor";
        }
        if($rollentry->getRole() === 0){
            return "student";
        }
    }
}
