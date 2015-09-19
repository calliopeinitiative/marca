<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assignment
 *
 * @ORM\Table(name="assignment")
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\AssignmentRepository")
 */
class Assignment
{
    const shared_instructor=0;
    const shared_class=1;

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
     * @ORM\Column(name="instructions", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="assignment")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course", inversedBy="assignments")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", nullable=true)
     */
    protected $course;

    /**
     * @ORM\ManyToMany(targetEntity="Marca\FileBundle\Entity\File")
     */
    protected $resources;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\FileBundle\Entity\File")
     */
    protected $template;

    /**
     * @ORM\OneToMany(targetEntity="Marca\AssignmentBundle\Entity\AssignmentStage", mappedBy="assignment", cascade={"persist"})
     */
    protected $stages;

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
     * @return Assignment
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
     * Set instructions
     *
     * @param string $instructions
     * @return Assignment
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string 
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set shared
     *
     * @param integer $shared
     * @return Assignment
     */
    public function setShared($shared)
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * Get shared
     *
     * @return integer 
     */
    public function getShared()
    {
        return $this->shared;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resources = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Marca\UserBundle\Entity\User $user
     * @return Assignment
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
     * Set course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     * @return Assignment
     */
    public function setCourse(\Marca\CourseBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
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

    /**
     * Add resources
     *
     * @param \Marca\FileBundle\File $resources
     * @return Assignment
     */
    public function addResource(\Marca\FileBundle\Entity\File $resources)
    {
        $this->resources[] = $resources;

        return $this;
    }

    /**
     * Remove resources
     *
     * @param \Marca\FileBundle\File $resources
     */
    public function removeResource(\Marca\FileBundle\Entity\File $resources)
    {
        $this->resources->removeElement($resources);
    }

    /**
     * Get resources
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set template
     *
     * @param \Marca\FileBundle\File $template
     * @return Assignment
     */
    public function setTemplate(\Marca\FileBundle\Entity\File $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \Marca\FileBundle\File 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Add stages
     *
     * @param \Marca\AssignmentBundle\AssignmentStage $stages
     * @return Assignment
     */
    public function addStage(\Marca\AssignmentBundle\Entity\AssignmentStage $stages)
    {
        $this->stages[] = $stages;
        $stages->setAssignment($this);
        return $this;
    }

    /**
     * Remove stages
     *
     * @param \Marca\AssignmentBundle\AssignmentStage $stages
     */
    public function removeStage(\Marca\AssignmentBundle\Entity\AssignmentStage $stages)
    {
        $this->stages->removeElement($stages);
    }

    /**
     * Get stages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Assignment
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
}
