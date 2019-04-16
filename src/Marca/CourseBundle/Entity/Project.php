<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Marca\CourseBundle\Entity\Project
 *
 * @ORM\Table(name="project")
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="projects")
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
    private $resource = false;

    /**
     * @var boolean $coursehome
     *
     * @ORM\Column(name="coursehome", type="boolean", nullable=true)
     */
    private $coursehome = false;


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
     * @param \Marca\CourseBundle\Entity\Course $course
     */
    public function setCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
     * Get course
     *
     * @return \Marca\CourseBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }
    
    //function for comparing and sorting projects by sortOrder
    static function cmp_sortOrder($a, $b)
    {
        if($a->sortOrder === $b->sortOrder) {
            return 0;
        }
        return($a->sortOrder > $b->sortOrder) ? +1 : -1;
    }
    

    /**
     * Set coursehome
     *
     * @param boolean $coursehome
     * @return Project
     */
    public function setCoursehome($coursehome)
    {
        $this->coursehome = $coursehome;
    
        return $this;
    }

    /**
     * Get coursehome
     *
     * @return boolean 
     */
    public function getCoursehome()
    {
        return $this->coursehome;
    }
}
