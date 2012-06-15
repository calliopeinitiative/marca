<?php

namespace Marca\TagBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends EntityRepository
{
   public function findTagsByUser($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t from MarcaTagBundle:Tag t WHERE t.user = ?1 ORDER BY t.sort')->setParameter('1',$user)->getResult();
    } 
    
   public function findTagsByTagset($id)
    {  
       return $this->getEntityManager()
               ->createQuery('SELECT t,s from MarcaTagBundle:Tag t JOIN t.tagset s WHERE s.id = ?1 ORDER BY t.sort DESC')
               ->setParameter('1',$id)->getResult();
    }
}