<?php

namespace Marca\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 */
class UserRepository extends EntityRepository
{       
    public function findUsersAlphaOrder()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u from MarcaUserBundle:User u ORDER BY u.lastname')->getResult();
    } 
   
}