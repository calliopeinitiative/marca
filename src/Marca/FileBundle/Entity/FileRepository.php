<?php

namespace Marca\FileBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FileRepository
 *
 * This class was generated by the Doctrine ORM.
 */
class FileRepository extends EntityRepository
{
    /**
     * Find files by user
     * Access is limited by role in course, instructors can see everything
     */
    public function findFilesByUser($user, $course, $access)
    {
        if ($access ==2 ) {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b WHERE f.user = ?1 AND f.course = ?2 AND  f.reviewed IS NULL AND
                p.resource = false
                ORDER BY  f.updated DESC')->setParameter('1', $user)->setParameter('2', $course)->getResult();
        }
        else {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b WHERE f.user = ?1 AND f.course = ?2 AND f.access = 1 AND f.reviewed
                 IS NULL AND p.resource = false
                ORDER BY  f.updated DESC')->setParameter('1', $user)->setParameter('2', $course)->getResult();
        }

    }

    /**
     * Find reviews by user
     * Access is limited by role in course, instructors can see everything
     */
    public function findReviewsByUser($user, $course, $access)
    {
        if ($access ==2 ) {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b WHERE f.user = ?1 AND f.course = ?2 AND  f.reviewed IS NOT NULL AND
                p.resource = false
                ORDER BY  f.updated DESC')->setParameter('1', $user)->setParameter('2', $course)->getResult();
        }
        else {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b WHERE f.user = ?1 AND f.course = ?2 AND f.access = 1 AND f.reviewed
                 IS NOT NULL AND p.resource = false
                ORDER BY  f.updated DESC')->setParameter('1', $user)->setParameter('2', $course)->getResult();
        }

    }


    /**
     * Find Files by Project
     * Access is limited by role in course, instructors can see everything
     */
    public function findFilesByProject($project, $access)
    {
        if ($access ==2 ) {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b
                WHERE f.project = ?1 AND f.reviewed IS NULL ORDER BY  f.name ASC')
                ->setParameter('1', $project)->getResult();
        }
        else {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b WHERE f.project = ?1  AND f.access = 1 AND f.reviewed IS NULL ORDER
                 BY  f.name ASC')
                ->setParameter('1', $project)->getResult();
        }

    }

    /**
     * Find all Shared Files
     * Access is limited by role in course, instructors can see everything
     */
    public function findSharedFiles($course, $access)
    {
        if ($access ==2 ) {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b
                WHERE f.course = ?1 AND f.reviewed IS NULL AND p.resource = false ORDER BY  f.updated DESC')
                ->setParameter('1', $course)->getResult();
        }
        else {
            return $this->getEntityManager()
                ->createQuery('SELECT f, p, d, t, r, o, b, g FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN
                f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b WHERE f.course = ?1  AND f.access = 1 AND f.reviewed IS NULL  AND
                p.resource = false ORDER BY  f.updated DESC')
                ->setParameter('1', $course)->getResult();
        }

    }

    /**
    * First users files with shared access for portfolio
   */
     public function findFilesForPort($user, $course)
    {
          return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, b, g  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN f.grade g LEFT JOIN f.tag t LEFT JOIN f.reviews r  LEFT JOIN f.feedback b
                WHERE f.reviewed IS NULL AND f.user = ?1 AND p.course=?2 AND p.resource= FALSE AND f.access = 1  ORDER BY  f.updated DESC')
                ->setParameter('1',$user)->setParameter('2',$course)->getResult();
    }

    /**
     * First users files with shared access for portfolio
     */
    public function findReviewsForPort($user, $course)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, b, g  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.portfolio o  LEFT JOIN f.grade g LEFT JOIN f.tag t
            LEFT JOIN f.reviewed r  LEFT JOIN f.feedback b
            WHERE f.reviewed IS NOT NULL AND f.user = ?1 AND p.course=?2 AND p.resource = FALSE AND r.access = 1  ORDER BY  f.updated DESC')
            ->setParameter('1',$user)->setParameter('2',$course)->getResult();
    }
    
 /**
 * First all users files for portfolios allowing private documents
 */
  public function findFilesForPrivatePort($project, $user, $course)
    {

       if($project == 'recent') {
         return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.responses s LEFT JOIN f.portfolio o LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.course = ?1 AND p.resource = false AND f.reviewed IS NULL AND f.user = ?2 ORDER BY f.updated DESC')
                ->setParameter('1',$course)->setParameter('2',$user)->getResult();
       } else {
          return $this->getEntityManager()
            ->createQuery('SELECT f, p, d, t, r, o, s  FROM MarcaFileBundle:File f JOIN f.project p LEFT JOIN f.doc d LEFT JOIN f.responses s LEFT JOIN f.portfolio o LEFT JOIN f.tag t LEFT JOIN f.reviews r
                WHERE f.project = ?1 AND f.reviewed IS NULL AND f.user = ?2 ORDER BY  f.name ASC')
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
                ->createQuery('SELECT f from MarcaFileBundle:File f JOIN f.project p WHERE (f.course = ?1 OR f.course in (?2)) AND p.coursehome=true AND f.access = 1 ORDER BY  f.name ASC')
                ->setParameters(array('1' =>  $course))->setParameter('2',$parents)->getResult();
        } else {
            return $this->getEntityManager()
                ->createQuery('SELECT f from MarcaFileBundle:File f JOIN f.project p WHERE f.course = ?1 AND p.coursehome=true AND f.access = 1 ORDER BY  f.name ASC')
                ->setParameters(array('1' =>  $course))->getResult();
        }



    }
}
