<?php

namespace Marca\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="coupon")
 * @ORM\Entity(repositoryClass="Marca\AdminBundle\Entity\CouponRepository")
 */
class Coupon
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;
    

    /**
     * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Term", inversedBy="coupons", cascade={"remove"})
     * @ORM\JoinColumn(name="term_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $term; 
    
    /**
     * @ORM\OneToOne(targetEntity="Marca\UserBundle\Entity\User", mappedBy="coupon", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="oldcoupons", cascade={"persist"})
     * @ORM\JoinColumn(name="past_user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $pastuser;

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
     * Set code
     *
     * @param string $code
     * @return Coupon
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set term
     *
     * @param \Marca\CourseBundle\Entity\Term $term
     * @return Coupon
     */
    public function setTerm(\Marca\CourseBundle\Entity\Term $term = null)
    {
        $this->term = $term;
    
        return $this;
    }

    /**
     * Get term
     *
     * @return \Marca\CourseBundle\Entity\Term 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set user
     *
     * @param \Marca\UserBundle\Entity\User $user
     * @return Coupon
     */
    public function setUser(\Marca\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Marca\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        
    }
    
    

    /**
     * Set pastuser
     *
     * @param \Marca\UserBundle\Entity\User $pastuser
     * @return Coupon
     */
    public function setPastuser(\Marca\UserBundle\Entity\User $pastuser = null)
    {
        $this->pastuser = $pastuser;
    
        return $this;
    }

    /**
     * Get pastuser
     *
     * @return \Marca\UserBundle\Entity\User 
     */
    public function getPastuser()
    {
        return $this->pastuser;
    }
}