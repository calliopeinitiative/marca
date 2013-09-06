<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\CourseBundle\Entity\Term
 *
 * @ORM\Table(name="term")
 * @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\TermRepository")
 */
class Term
{
    const STATUS_INACTIVE=0;
    const STATUS_ACTIVE=1;
    const STATUS_CONTINUING=2;
    
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
    private $term = 0;

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
     * @ORM\ManyToOne(targetEntity="Marca\AdminBundle\Entity\Institution", inversedBy="terms", cascade={"remove"})
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $institution;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Marca\AdminBundle\Entity\Coupon", mappedBy="term", cascade={"persist"})
     */
    protected $coupons;    


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

    /**
     * Set institution
     *
     * @param \Marca\AdminBundle\Entity\Institution $institution
     * @return Term
     */
    public function setInstitution(\Marca\AdminBundle\Entity\Institution $institution = null)
    {
        $this->institution = $institution;
    
        return $this;
    }

    /**
     * Get institution
     *
     * @return \Marca\AdminBundle\Entity\Institution 
     */
    public function getInstitution()
    {
        return $this->institution;
    }
}