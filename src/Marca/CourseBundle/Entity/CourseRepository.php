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


    public function findParents($course) {
        return $this->createQueryBuilder('c')
            ->select('p.id')
            ->join('c.parents', 'p')
            ->andWhere('c.id = :course')
            ->setParameter('course', $course)
            ->getQuery()
            ->getResult();

    }

   public function findCourseByLastname($lastname)
    {
       $lastname = strtolower(trim($lastname));
       $lastname = $lastname.'%';
        return $this->getEntityManager()
            ->createQuery("SELECT u.lastname,u.firstname,c.name,c.time,c.id from MarcaCourseBundle:Roll r JOIN r.user u JOIN r.course c JOIN c.term t
                WHERE c.enroll=True AND c.module=0 AND r.role=2 AND t.status > 0 AND t.status < 3  AND LOWER(u.lastname) LIKE ?1 ORDER BY c.name")->setParameter('1',$lastname)->getResult();
    }
   
    
    public function findCoursesByUser($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c from MarcaCourseBundle:Course c WHERE c.user = ?1 ORDER BY c.name')->setParameter('1',$user)->getResult();
    }     
         
    public function findEnrolledCourses($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c from MarcaCourseBundle:Course c JOIN c.roll r JOIN c.term t WHERE r.user = ?1 AND r.role > 0 AND t.status > 0 AND t.status < 3 AND c.module = 0 ORDER BY r.role,c.name')->setParameter('1',$user)->getResult();
    } 
    
    public function findPendingCourses($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c from MarcaCourseBundle:Course c JOIN c.roll r WHERE r.user = ?1 and r.role = 0 ORDER BY c.name')->setParameter('1',$user)->getResult();
    } 
    
    public function findModules($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c from MarcaCourseBundle:Course c JOIN c.roll r JOIN c.term t WHERE r.user = ?1 AND r.role > 0 AND c.module > 0  AND t.status < 3 ORDER BY c.name')->setParameter('1',$user)->getResult();
    }

    public function findArchivedCourses($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c from MarcaCourseBundle:Course c JOIN c.roll r JOIN c.term t WHERE r.user = ?1 AND r.role > 0 AND r.role < 3 AND t.status = 0 AND t.status < 3 AND c
.module = 0
ORDER BY c.name')->setParameter('1',$user)->getResult();

    }     
    
    public function findUserCourseIds($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c.id from MarcaCourseBundle:Course c JOIN c.roll r WHERE r.user = ?1')->setParameter('1',$user)->getResult();
    }

    public function findCoursesByTerm($term)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c.id from MarcaCourseBundle:Course c  WHERE c.term = ?1')->setParameter('1',$term)->getResult();
    }

    public function findDefaultModules() {
        $modules = $this->createQueryBuilder('c')
            ->andWhere('c.module=3')
            ->getQuery()
            ->getResult();
        return $modules;
    }
}