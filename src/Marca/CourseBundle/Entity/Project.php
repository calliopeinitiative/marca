<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Marca\CourseBundle\Entity\Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\ProjectRepository")
 */
class Project
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer $userid
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="project")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $course; 
    
    
    /**
     * @var integer $sortOrder
     *
     * @ORM\Column(name="sortOrder", type="integer")
     */
    private $sortOrder;

    /**
     * @var boolean $resource
     *
     * @ORM\Column(name="resource", type="boolean")
     */
    private $resource;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set courseid
     *
     * @param integer $courseid
     */
    public function setCourseid($courseid)
    {
        $this->courseid = $courseid;
    }

    /**
     * Get courseid
     *
     * @return integer 
     */
    public function getCourseid()
    {
        return $this->courseid;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get sortOrder
     *
     * @return integer 
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set resource
     *
     * @param boolean $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource
     *
     * @return boolean 
     */
    public function getResource()
    {
        return $this->resource;
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
    
}