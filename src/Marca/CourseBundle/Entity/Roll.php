<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\CourseBundle\Entity\Roll
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\RollRepository")
 */
class Roll
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
     * @var integer $role
     *
     * @ORM\Column(name="role", type="integer")
     */
    private $role;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="roll")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $course; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\Profile", inversedBy="roll")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $profile; 
    
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
     * Set role
     *
     * @param integer $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
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
     * Set course
     *
     * @param Marca\CourseBundle\Entity\Course $course
     */
    public function setCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get course
     *
     * @return Marca\CourseBundle\Entity\Course 
     */
    public function getCourse()
    {
        return $this->course;
    }



    /**
     * Set profile
     *
     * @param Marca\UserBundle\Entity\Profile $profile
     */
    public function setProfile(\Marca\UserBundle\Entity\Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @return Marca\UserBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}