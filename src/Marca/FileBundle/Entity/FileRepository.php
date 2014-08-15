<?php

namespace Marca\FileBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FileRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FileRepository extends EntityRepository
{
/**
 * First conditional catches sort order by name, project, tag or updated
 * Second conditional catches Recent Files listing (with no associated project defined so it can be omitted from seach.
 * Sort is not currently working; need to refactor the join
 */
   public function findFilesByProject($project, $user, $scope, $course, $tag, $resource, $byuser, $role)
    {
        if($scope == 'all' and $role == 2) {$scopeQuery = ' or f.access = 1 or f.access = 0';}
        elseif($scope == 'all' and $role != 2) {$scopeQuery = ' or f.access = 1';}
        elseif($scope == 'byuser' and $role == 2) {$user = $byuser; $scopeQuery = '';}
        elseif($scope == 'byuser' and $role != 2) {$user = $byuser; $scopeQuery = ' AND f.access = 1';}
        else {$scopeQuery = '';};
       
        if ($tag != 0) {$tagQuery = '';} else {$tagQuery = ' OR t.id != 0 OR t.id is NULL';}

        if($project == 'recent') {
         return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s, g  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.grade g LEFT JOIN f.responses s LEFT JOIN f.portfolio o LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.course = ?1 AND p.resource = ?3 AND f.reviewed IS NULL AND (f.user = ?2'.$scopeQuery.') AND (t.id = ?4'.$tagQuery.') ORDER BY f.updated DESC')
                ->setParameter('1',$course)->setParameter('2',$user)->setParameter('3',$resource)->setParameter('4',$tag)->getResult();
       } else {
          return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.grade g LEFT JOIN f.responses s LEFT JOIN f.portfolio o  LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.project = ?1 AND f.reviewed IS NULL AND (f.user = ?3'.$scopeQuery.') AND (t.id = ?4'.$tagQuery.') ORDER BY  f.updated DESC')
                ->setParameter('1',$project)->setParameter('3',$user)->setParameter('4',$tag)->getResult();
       };

    }

/**
 * First users files with shared access for portfolio
 */
  public function findFilesForPort($project, $user, $course)
    {

       if($project == 'recent') {
         return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.responses s LEFT JOIN f.portfolio o LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.course = ?1 AND p.resource = false AND f.reviewed IS NULL AND f.user = ?2 AND f.access = 1  ORDER BY f.updated DESC')
                ->setParameter('1',$course)->setParameter('2',$user)->getResult();
       } else {
          return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.responses s LEFT JOIN f.portfolio o LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.project = ?1 AND f.reviewed IS NULL AND f.user = ?2 AND f.access = 1  ORDER BY  f.name ASC')
                ->setParameter('1',$project)->setParameter('2',$user)->getResult();
       };

    }

    /**
     * check for files for project delete
     */
    public function checkProjectFiles($project, $user)
    {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p  FROM MarcaFileBundle:File f JOIN f.project p WHERE f.project = ?1 AND f.user = ?2')
                ->setParameter('1',$project)->setParameter('2',$user)->getResult();
    }


   public function findPeerReviewFiles ($project, $user, $scope, $course, $tag, $resource, $byuser, $role)
    {

        if($project == 'recent') {
         return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.responses s LEFT JOIN f.portfolio o  LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.course = ?1 AND p.resource = ?3 AND (r.user = ?2) ORDER BY f.updated DESC')
                ->setParameter('1',$course)->setParameter('2',$user)->setParameter('3',$resource)->getResult();
       } else {
          return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.responses s LEFT JOIN f.portfolio o  LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.project = ?1 AND (r.user = ?3) ORDER BY  f.name ASC')
                ->setParameter('1',$project)->setParameter('3',$user)->getResult();
       };

   }
    
    public function deleteEdoc($id)
    {
         return $this->getEntityManager()
            ->createQuery('DELETE MarcaFileBundle:File f WHERE f.id = ?1')
                ->setParameter('1',$id)->getResult(); 
    } 
    
    public function countFilesByUser($user, $course)
    {
       return $this->getEntityManager()
               ->createQuery('SELECT f.id from MarcaFileBundle:File f WHERE f.user = ?1 AND f.course = ?2')
               ->setParameters(array('1' => $user, '2' => $course))->getResult();
    } 
    
    public function findHidden($user, $course)
    {
       return $this->getEntityManager()
               ->createQuery('SELECT f from MarcaFileBundle:File f WHERE f.user = ?1 AND f.course = ?2 AND f.access = 2' )
               ->setParameters(array('1' => $user, '2' => $course))->getResult();
    }     
    
    public function countFilesByCourse($course)
    {
       return $this->getEntityManager()
               ->createQuery('SELECT f.id from MarcaFileBundle:File f WHERE f.course = ?1')
               ->setParameters(array('1' => $course))->getResult();
    }

    public function findCoursehomeFiles($course)
    {
        $parents = $this->getEntityManager()
            ->createQuery('SELECT p.id from MarcaCourseBundle:Course c JOIN c.parents p WHERE c.id = ?1')->setParameter('1',$course)->getResult();
        if ($parents) {
            return $this->getEntityManager()
                ->createQuery('SELECT f from MarcaFileBundle:File f JOIN f.project p WHERE (f.course = ?1 OR f.course in (?2)) AND p.coursehome=true AND f.access = 1')
                ->setParameters(array('1' =>  $course))->setParameter('2',$parents)->getResult();
        } else {
            return $this->getEntityManager()
                ->createQuery('SELECT f from MarcaFileBundle:File f JOIN f.project p WHERE f.course = ?1 AND p.coursehome=true AND f.access = 1')
                ->setParameters(array('1' =>  $course))->getResult();
        }



    }




}