<?php

namespace Marca\GradebookBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GradeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GradeRepository extends EntityRepository
{

    public function findGradesByCourse($user,$course)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT g from MarcaGradebookBundle:Grade g JOIN g.category c JOIN c.gradeset s  WHERE g.user = ?1 AND s.course = ?2")
            ->setParameters(array('1' => $user,'2' => $course))->getResult();
    }


}