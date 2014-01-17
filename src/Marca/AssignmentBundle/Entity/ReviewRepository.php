<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReviewRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReviewRepository extends EntityRepository
{
  public function findReviewsByFile($file)
    {  
       return $this->getEntityManager()
               ->createQuery('SELECT r from MarcaAssignmentBundle:Review r WHERE r.file = ?1 ORDER BY r.created DESC')
               ->setParameters(array('1' => $file))->getResult();
    }
}