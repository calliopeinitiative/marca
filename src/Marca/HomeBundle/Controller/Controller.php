<?php

namespace Marca\HomeBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Controller extends SymfonyController
{
    const ROLE_PENDING=0;
    const ROLE_STUDENT=1;
    const ROLE_INSTRUCTOR=2;
    const ROLE_TA=3;
    const ROLE_PORTREVIEW=4;


    /**
     *
     * @return EntityManager
     */   
    protected function getEm() {
        $em = $this->getDoctrine()->getManager();
        return $em;
    }    
    
    /**
     *
     * @return \Marca\CourseBundle\Entity\Course
     */
    protected function getCourse(Request $request) {
        $courseid = $request->attributes->get('courseid');
        $course = $this->getEm()->getRepository('MarcaCourseBundle:Course')->find($courseid);
        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity');
        }
        return $course;
    } 

    /**
     *
     * @return \Marca\CourseBundle\Entity\Roll
     */
    protected function getRoll(Request $request) {
        $courseid = $request->attributes->get('courseid');
        $roll = $this->getEm()->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        if (!$roll) {
            throw $this->createNotFoundException('Unable to find Roll entity');
        }
        return $roll;
    } 
    
    /**
     *@return string 
     */
    protected function getCourseRole(Request $request){
        $course = $this->getCourse($request);
        $user = $this->getUser();
        $rollentry = $this->getEm()->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        if(!$rollentry){
            return null;
        }
        else{
            $role = $rollentry->getRole();
            return $role;
        }
    }
    /*
     * checks if the current user is in the array of allowed roles
     * if not, throws an access denied exception
     * if so, returns true
     * thanks to Wyatt Peterson for design help
     */
    protected function restrictAccessTo(Request $request, $allowed){
        //grab our current user's role in our current course
        $currentUserRoles = $this->getCourseRole($request);
        //test if user role is not in the array of allowed roles
        if(!in_array($currentUserRoles, $allowed)){
            throw new AccessDeniedException();
        } else {
            return true;
        }
    }
    
}
