<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Review
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\ReviewRepository")
 */
class Review
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
     * @var integer
     *
     * @ORM\Column(name="grade", type="integer")
     */
    private $grade;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text")
     */
    private $notes;

    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\FileBundle\Entity\File")
    */
    protected $file;    
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course")
    */
    protected $course;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $reviewer; 
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\AssignmentBundle\Entity\ReviewRubric", inversedBy="reviews")
    */
    protected $reviewrubric; 
        
    
    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @Gedmo\Timestampable(on="create")
    */
    protected $created;
    
    
    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @Gedmo\Timestampable(on="update")
    */
    protected $updated;    
    
    

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
     * Set grade
     *
     * @param integer $grade
     * @return Review
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    
        return $this;
    }

    /**
     * Get grade
     *
     * @return integer 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Review
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    
        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ratings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Review
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Review
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     * @return Review
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
     * Get reviewrubric
     *
     * @return \Marca\AssignmentBundle\Entity\ReviewRubric 
     */
    public function getReviewrubric()
    {
        return $this->reviewrubric;
    }



    /**
     * Set reviewer
     *
     * @param \Marca\UserBundle\Entity\User $reviewer
     * @return Review
     */
    public function setReviewer(\Marca\UserBundle\Entity\User $reviewer = null)
    {
        $this->reviewer = $reviewer;
    
        return $this;
    }

    /**
     * Get reviewer
     *
     * @return \Marca\UserBundle\Entity\User 
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * Set reviewrubric
     *
     * @param \Marca\AssignmentBundle\Entity\ReviewRubric $reviewrubric
     * @return Review
     */
    public function setReviewrubric(\Marca\AssignmentBundle\Entity\ReviewRubric $reviewrubric = null)
    {
        $this->reviewrubric = $reviewrubric;
    
        return $this;
    }
}