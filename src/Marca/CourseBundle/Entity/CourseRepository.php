<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CourseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CourseRepository extends EntityRepository
{
   public function findCourseByLastname($lastname)
    {
       $lastname == $lastname.'%';
        return $this->getEntityManager()
            ->createQuery('SELECT p.lastname,p.firstname,c.name,c.time,c.id from MarcaCourseBundle:Roll r JOIN r.profile p JOIN r.course c
                WHERE r.role=1 AND p.lastname LIKE ?1 ORDER BY c.name')->setParameter('1',$lastname)->getResult();
    }
   
    public function findCoursesByUserId($userid)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p.lastname,p.firstname,c.name,c.time,c.id from MarcaCourseBundle:Roll r JOIN r.profile p JOIN r.course c
                WHERE p.id = ?1 ORDER BY c.name')->setParameter('1',$userid)->getResult();
    }  
    
    public function findCoursesByUser($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c from MarcaCourseBundle:Course c WHERE c.user = ?1 ORDER BY c.name')->setParameter('1',$user)->getResult();
    }     
         
    
}