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
    public function findForumRecent($user, $set)
    {  
       $offset = $set * 5;
       return $this->getEntityManager()
               ->createQuery('SELECT f from MarcaForumBundle:Forum f WHERE f.user = ?1 ORDER BY f.created DESC')
               ->setParameter('1',$user)->setMaxResults(5)->setFirstResult($offset)->getResult();
    }
}