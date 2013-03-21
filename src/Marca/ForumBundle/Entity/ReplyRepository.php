<?php

namespace Marca\ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReplyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReplyRepository extends EntityRepository
{
    public function countRepliesByUser($user,$course)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r.id FROM MarcaForumBundle:Reply r JOIN r.comment c JOIN c.forum f WHERE r.user = ?1 AND f.course = ?2')
                ->setParameters(array('1' => $user, '2' => $course))->getResult();
    } 

    public function countRepliesByCourse($course)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r.id FROM MarcaForumBundle:Reply r JOIN r.comment c JOIN c.forum f WHERE f.course = ?1')
                ->setParameter('1',$course)->getResult();
    }    
}