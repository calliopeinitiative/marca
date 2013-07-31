<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ReviewRubric
 *
 * @ORM\Table(name="reviewrubric")
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\ReviewRubricRepository")
 */
class ReviewRubric
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
     * @ORM\Column(name="name", type="string", length=60)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="instructions", type="text")
     */
    private $instructions;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="shared", type="integer")
     */
    private $shared;

    /**
     * @ORM\OneToMany(targetEntity="PromptItem", mappedBy="reviewrubric")
     */
    private $promptitems;
    
    /**
     * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Project", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $project;
    
    /**
     * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;
    
   /**
    * @ORM\ManyToMany(targetEntity="Marca\DocBundle\Entity\Markupset")
    * @ORM\JoinTable(name="reviewrubric_markupset", 
    *       joinColumns={@ORM\JoinColumn(name="ReviewRubric_id", referencedColumnName="id")},
    *       inverseJoinColumns={@ORM\JoinColumn(name="Markupset_id", referencedColumnName="id")})
    */
    private $markupsets;
    
    public function __construct() {
        $this->promptitems=new ArrayCollection();
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
     * @return ReviewRubric
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
     * @return ReviewRubric
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
     * Set instructions
     *
     * @param string $instructions
     * @return ReviewRubric
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
     * Set keywords
     *
     * @param string $keywords
     * @return ReviewRubric
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set shared
     *
     * @param integer $shared
     * @return ReviewRubric
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
}
