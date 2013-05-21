<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\CourseBundle\Entity\Term
 *
 * @ORM\Table(name="term")
 * @ORM\Entity
 */
class Term
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $term
     *
     * @ORM\Column(name="term", type="string", length=255)
     */
    private $term;

    /**
     * @var string $termName
     *
     * @ORM\Column(name="termName", type="string", length=255)
     */
    private $termName;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set term
     *
     * @param string $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * Get term
     *
     * @return string 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set termName
     *
     * @param string $termName
     */
    public function setTermName($termName)
    {
        $this->termName = $termName;
    }

    /**
     * Get termName
     *
     * @return string 
     */
    public function getTermName()
    {
        return $this->termName;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
}