<?php

namespace Marca\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $course = $this->getCourse();
        $user = $this->getUser();
        $rollentry = $this->getEm()->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        if(!$rollentry){
            return array("none");
        }
        else{
            $roles = $rollentry->getRole();
            return $roles;
        }
    }
    /*
     * checks if the current user is in the array of allowed roles
     * if not, throws an access denied exception
     * if so, returns true
     * thanks to Wyatt Peterson for design help
     */
    public function restrictAccessTo($allowed){
        //grab our current user's role in our current course
        $currentUserRoles = $this->getCourseRole();
        //test if user role is not in the array of allowed roles
        if(!array_intersect($currentUserRoles, $allowed)){
            throw new AccessDeniedException();
        } else {
            return true;
        }
    }
    
}
