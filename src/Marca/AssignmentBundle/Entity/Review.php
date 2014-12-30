<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Review
 *
 * @ORM\Table(name="review")
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
     * @ORM\Column(name="grade", type="integer", nullable=true)
     */
    private $grade;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\FileBundle\Entity\File", inversedBy="feedback")
    * @ORM\JoinColumn(onDelete="cascade")
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
    * @ORM\OneToMany(targetEntity="ReviewResponse", mappedBy="review")
    */
    protected $reviewresponses;      
        
    
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
     * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Scale")
     */
    protected $feedbackRating;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="feedbackgrade", type="integer", nullable=true)
     */
    protected $feedbackGrade;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="feedbackcomment", type="string", length=750, nullable=true)
     */
    protected $feedbackComment;
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

    /**
     * Set file
     *
     * @param \Marca\FileBundle\Entity\File $file
     * @return Review
     */
    public function setFile(\Marca\FileBundle\Entity\File $file = null)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return \Marca\FileBundle\Entity\File 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Add reviewresponses
     *
     * @param \Marca\AssignmentBundle\Entity\ReviewResponse $reviewresponses
     * @return Review
     */
    public function addReviewresponse(\Marca\AssignmentBundle\Entity\ReviewResponse $reviewresponses)
    {
        $this->reviewresponses[] = $reviewresponses;
    
        return $this;
    }

    /**
     * Remove reviewresponses
     *
     * @param \Marca\AssignmentBundle\Entity\ReviewResponse $reviewresponses
     */
    public function removeReviewresponse(\Marca\AssignmentBundle\Entity\ReviewResponse $reviewresponses)
    {
        $this->reviewresponses->removeElement($reviewresponses);
    }

    /**
     * Get reviewresponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviewresponses()
    {
        return $this->reviewresponses;
    }

    /**
     * Set feedbackGrade
     *
     * @param integer $feedbackGrade
     * @return Review
     */
    public function setFeedbackGrade($feedbackGrade)
    {
        $this->feedbackGrade = $feedbackGrade;
    
        return $this;
    }

    /**
     * Get feedbackGrade
     *
     * @return integer 
     */
    public function getFeedbackGrade()
    {
        return $this->feedbackGrade;
    }

    /**
     * Set feedbackComment
     *
     * @param string $feedbackComment
     * @return Review
     */
    public function setFeedbackComment($feedbackComment)
    {
        $this->feedbackComment = $feedbackComment;
    
        return $this;
    }

    /**
     * Get feedbackComment
     *
     * @return string 
     */
    public function getFeedbackComment()
    {
        return $this->feedbackComment;
    }

    /**
     * Set feedbackRating
     *
     * @param \Marca\AssessmentBundle\Entity\Scale $feedbackRating
     * @return Review
     */
    public function setFeedbackRating(\Marca\AssessmentBundle\Entity\Scale $feedbackRating = null)
    {
        $this->feedbackRating = $feedbackRating;
    
        return $this;
    }

    /**
     * Get feedbackRating
     *
     * @return \Marca\AssessmentBundle\Entity\Scale 
     */
    public function getFeedbackRating()
    {
        return $this->feedbackRating;
    }
}
