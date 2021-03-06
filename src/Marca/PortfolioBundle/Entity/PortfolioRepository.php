<?php

namespace Marca\PortfolioBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PortfolioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PortfolioRepository extends EntityRepository
{
    public function findByUser($user,$course)
    {  
       return $this->getEntityManager()
               ->createQuery('SELECT p from MarcaPortfolioBundle:Portfolio p WHERE p.user = ?1 AND p.course = ?2 ORDER BY p.portorder ASC')
               ->setParameter('1',$user)->setParameter('2',$course)->getResult();
    }

    public function findShownOnly($user,$course)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p from MarcaPortfolioBundle:Portfolio p join p.portitem i WHERE p.user = ?1 AND p.course = ?2 AND (i.status=1 or i.status is null) ORDER BY p.portorder ASC')
            ->setParameter('1',$user)->setParameter('2',$course)->getResult();
    }
}