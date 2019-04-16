<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="instructions", type="text", nullable=true)
     */
    private $instructions;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="shared", type="integer")
     */
    private $shared;

    /**
     * @ORM\OneToMany(targetEntity="PromptItem", mappedBy="reviewRubric")
     * @ORM\OrderBy({"sortOrder" = "ASC"})
     */
    private $promptitems;
    
    /**
     * @ORM\ManyToMany(targetEntity="Marca\CourseBundle\Entity\Course")
     * @ORM\JoinTable(name="reviewrubric_course", 
     *       joinColumns={@ORM\JoinColumn(name="ReviewRubric_id", referencedColumnName="id")},
     *       inverseJoinColumns={@ORM\JoinColumn(name="Course_id", referencedColumnName="id")})
     */
    private $courses;
    
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
     
    /**
    * @ORM\OneToMany(targetEntity="Review", mappedBy="reviewrubric")
    */
    protected $reviews;
    
    
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

    /**
     * Add promptitems
     *
     * @param \Marca\AssignmentBundle\Entity\PromptItem $promptitems
     * @return ReviewRubric
     */
    public function addPromptitem(\Marca\AssignmentBundle\Entity\PromptItem $promptitems)
    {
        $this->promptitems[] = $promptitems;
    
        return $this;
    }

    /**
     * Remove promptitems
     *
     * @param \Marca\AssignmentBundle\Entity\PromptItem $promptitems
     */
    public function removePromptitem(\Marca\AssignmentBundle\Entity\PromptItem $promptitems)
    {
        $this->promptitems->removeElement($promptitems);
    }

    /**
     * Get promptitems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromptitems()
    {
        return $this->promptitems;
    }

    /**
     * Set project
     *
     * @param \Marca\CourseBundle\Entity\Project $project
     * @return ReviewRubric
     */
    public function setProject(\Marca\CourseBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Marca\CourseBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set user
     *
     * @param \Marca\UserBundle\Entity\User $user
     * @return ReviewRubric
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
     * Add markupsets
     *
     * @param \Marca\DocBundle\Entity\Markupset $markupsets
     * @return ReviewRubric
     */
    public function addMarkupset(\Marca\DocBundle\Entity\Markupset $markupsets)
    {
        $this->markupsets[] = $markupsets;
    
        return $this;
    }

    /**
     * Remove markupsets
     *
     * @param \Marca\DocBundle\Entity\Markupset $markupsets
     */
    public function removeMarkupset(\Marca\DocBundle\Entity\Markupset $markupsets)
    {
        $this->markupsets->removeElement($markupsets);
    }

    /**
     * Get markupsets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarkupsets()
    {
        return $this->markupsets;
    }
}
