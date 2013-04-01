<?php

namespace Marca\AssessmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assessmentset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\AssessmentBundle\Entity\AssessmentsetRepository")
 */
class Assessmentset
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\AssessmentBundle\Entity\Objective", mappedBy="assessmentset")
    */
    protected $objectives;  
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\CourseBundle\Entity\Course", mappedBy="assessmentset")
    */
    protected $course;     


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
     * @return Assessmentset
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * Set description
     *
     * @param string $description
     * @return Assessmentset
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objectives = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add objectives
     *
     * @param \Marca\AssessmentBundle\Entity\Objective $objectives
     * @return Assessmentset
     */
    public function addObjective(\Marca\AssessmentBundle\Entity\Objective $objectives)
    {
        $this->objectives[] = $objectives;
    
        return $this;
    }

    /**
     * Remove objectives
     *
     * @param \Marca\AssessmentBundle\Entity\Objective $objectives
     */
    public function removeObjective(\Marca\AssessmentBundle\Entity\Objective $objectives)
    {
        $this->objectives->removeElement($objectives);
    }

    /**
     * Get objectives
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObjectives()
    {
        return $this->objectives;
    }

    /**
     * Add course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     * @return Assessmentset
     */
    public function addCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course[] = $course;
    
        return $this;
    }

    /**
     * Remove course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     */
    public function removeCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course->removeElement($course);
    }

    /**
     * Get course
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourse()
    {
        return $this->course;
    }
}