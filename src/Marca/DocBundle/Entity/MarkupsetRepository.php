<?php

namespace Marca\DocBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MarkupsetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MarkupsetRepository extends EntityRepository
{
    public function findPrivateMarkupSets($user){
        return $this->getEntityManager()
                ->createQuery('SELECT m from MarcaDocBundle:Markupset m JOIN m.users u WHERE u.id = ?1 AND m.shared = 0')->setParameter('1', $user->getId())->getResult();
    }
    
    public function findDefault(){
        return $this->getEntityManager()
                ->createQuery('SELECT m from MarcaDocBundle:Markupset m  WHERE m.shared = 2')->getResult();
    }    
    
}
