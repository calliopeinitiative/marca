<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Marca\CourseBundle\Entity\Course;

/**
 * RollRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RollRepository extends EntityRepository
{
       public function findRollByCourse($courseid)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r from MarcaCourseBundle:Roll r JOIN r.user u JOIN r.course c WHERE c.id = ?1 ORDER BY u.lastname,u.firstname')->setParameter('1',$courseid)->getResult();
    }

    public function findRollUser($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u,r from MarcaCourseBundle:Roll r JOIN r.user u WHERE r.id = ?1')->setParameter('1',$id)->getSingleResult();
    }
    
    public function findUserByRoll($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u.id from MarcaCourseBundle:Roll r JOIN r.user u WHERE r.id = ?1')->setParameter('1',$id)->getSingleResult();
    }    
    
    public function findUserInCourse($course, $user){
        try{
            $roleString = $this->getEntityManager()->createQuery('SELECT r from MarcaCourseBundle:Roll r WHERE r.user = ?1 AND r.course = ?2')->setParameter('1', $user->getId())->setParameter('2', $course->getId())->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e){
            $roleString = null;
        }
        return $roleString;
    }

    public function findPendingRoll($user){
        return $this->getEntityManager()->createQuery('SELECT r from MarcaCourseBundle:Roll r WHERE r.user = ?1 AND r.role = 0')->setParameter('1', $user)->getResult();
    }
    
    public function enroll($course,$user)
    {
        $em = $this->getEntityManager();
        $onroll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        if (!$onroll) {
            $roll  = new Roll();
            $roll->setCourse($course);
            $roll->setStatus('1');
            $roll->setRole(Roll::ROLE_PENDING);
            $roll->setUser($user);
            $em->persist($roll);
            $em->flush(); 
        }
           
    }    
}