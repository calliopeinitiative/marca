<?php

namespace Marca\AssessmentBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ScaleitemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScaleitemRepository extends EntityRepository
{
    /**
     * Find default scale
     *
     * @return Scale
     */
    public function findDefault($scale)
    {
        $default_scale = $this->createQueryBuilder('s')
            ->join('s.scale','sc')
            ->andWhere("sc.id = :scale")
            ->andWhere("s.value = :value")
            ->setParameter('value', 0)
            ->setParameter('scale', $scale)
            ->getQuery()
            ->getSingleResult();
        return $default_scale;
    }
}
