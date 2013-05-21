<?php

namespace Marca\CourseBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\CourseBundle\Entity\Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\TeamRepository")
 */
class Team
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
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     *@ORM\ManyToMany(targetEntity = "Marca\CourseBundle\Entity\Roll")
     *@ORM\JoinTable(name = "teams_rolls") 
     */
    protected $rolls;
    
    /**
    * @ORM\ManyToOne(targetEntity="Course", inversedBy="teams")
    * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
    */
    protected $course;

    public function __construct() {
        $this->rolls = new ArrayCollection();
    }
    
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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add rolls
     *
     * @param Marca\CourseBundle\Roll $rolls
     */
    public function addRoll(\Marca\CourseBundle\Entity\Roll $roll)
    {
        $roll->addTeam($this);
        $this->rolls[] = $roll;
    }

    /**
     * Get rolls
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRolls()
    {
        return $this->rolls;
    }
    
    /**
     *Set course
     * @param Marca\CourseBundle\Course $course 
     */
    public function setCourse($course)
    {
        $this->course=$course;
        
    }
    
    /**
     *Get course
     * 
     * @param Marca\CourseBundle\Course $course 
     */
    public function getCourse()
    {
        return $this->course;
    }
}