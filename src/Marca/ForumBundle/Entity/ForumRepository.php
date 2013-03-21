<?php

namespace Marca\ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ForumRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForumRepository extends EntityRepository
{
    
    public function findForumByCourse($course)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f FROM MarcaForumBundle:Forum f WHERE f.course = ?1')->setParameter('1',$course)->getResult();
    } 
    
    public function findForumDesc($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f FROM MarcaForumBundle:Forum f LEFT JOIN f.comments c LEFT JOIN c.replies r WHERE f.id = ?1 ORDER BY f.updated DESC')->setParameter('1',$id)->getSingleResult();
    } 
    
    public function countForumsByUser($user,$course)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.id FROM MarcaForumBundle:Forum f WHERE f.user = ?1 AND f.course = ?2')
                ->setParameters(array('1' => $user, '2' => $course))->getResult();
    }
    
    public function countForumsByCourse($course)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT f.id FROM MarcaForumBundle:Forum f WHERE f.course = ?1')
                ->setParameter('1',$course)->getResult();
    }     
}