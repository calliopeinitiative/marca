<?php

namespace Marca\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * InstitutionRepository
 *
 */
class InstitutionRepository extends EntityRepository
{       
    public function findDefault(){
        return $this->getEntityManager()
                ->createQuery('SELECT i from MarcaAdminBundle:Institution i')->setMaxResults(1)->getSingleResult();
    }        
}