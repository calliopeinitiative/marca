<?php

namespace Marca\GradebookBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AttendanceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AttendanceRepository extends EntityRepository
{
    public function countAbsenses($rollid)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT a from MarcaGradebookBundle:Attendance a WHERE a.roll = ?1 AND a.type = '0'")
            ->setParameters(array('1' => $rollid))->getResult();
    }
    public function countTardies($rollid)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT a from MarcaGradebookBundle:Attendance a WHERE a.roll = ?1 AND a.type = '1' ")
            ->setParameters(array('1' => $rollid))->getResult();
    }
}
