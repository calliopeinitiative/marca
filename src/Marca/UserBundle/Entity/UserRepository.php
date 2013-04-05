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

    public function findUsersByName($name)
    {
        $namesearch = '%' . $name . '%';
        return $this->getEntityManager()
            ->createQuery('SELECT u from MarcaUserBundle:User u WHERE u.lastname LIKE ?1 OR u.email LIKE ?1 OR u.username LIKE ?1 ORDER BY u.lastname')
                ->setParameters(array('1' => $namesearch))->getResult();
    }     
}