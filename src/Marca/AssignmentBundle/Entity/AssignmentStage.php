<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssignmentStage
 *
 * @ORM\Table(name="assignmentstage")
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\AssignmentStageRepository")
 */
class AssignmentStage
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
     * @ORM\Column(name="instructions", type="text")
     */
    private $instructions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dueDate", type="datetime")
     */
    private $dueDate;

    /**
     * @var integer
     * @ORM\Column(name="reviewsRequired", type="integer")
     */
    private $reviewsRequired;

    /**
     * @var string
     * @ORM\Column(name="reviewInstructions", type="text", nullable=true)
     */
    private $reviewInstructions;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\AssignmentBundle\Entity\Assignment", inversedBy="stages", cascade={"persist"})
     */
    protected $assignment;

    /**
     * @ORM\OneToOne(targetEntity="Marca\AssignmentBundle\Entity\AssignmentStage", mappedBy="previous")
     * @ORM\JoinColumn(name="next_id", referencedColumnName="id", nullable=true)
     */
    protected $next;

    /**
     * @ORM\OneToOne(targetEntity="Marca\AssignmentBundle\Entity\AssignmentStage", inversedBy="next")
     * @ORM\JoinColumn(name="previous_id",referencedColumnName="id", nullable=true)
     */
    protected $previous;

    /**
     * @ORM\OneToMany(targetEntity="Marca\AssignmentBundle\Entity\AssignmentSubmission", mappedBy="stage")
     */
    protected $submissions;


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
     * @return AssignmentStage
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
     * @return AssignmentStage
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
     * Set submitted
     *
     * @param boolean $submitted
     * @return AssignmentStage
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * Get submitted
     *
     * @return boolean 
     */
    public function getSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     * @return AssignmentStage
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime 
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set submittedDate
     *
     * @param \DateTime $submittedDate
     * @return AssignmentStage
     */
    public function setSubmittedDate($submittedDate)
    {
        $this->submittedDate = $submittedDate;

        return $this;
    }

    /**
     * Get submittedDate
     *
     * @return \DateTime 
     */
    public function getSubmittedDate()
    {
        return $this->submittedDate;
    }

    /**
     * Set revisionId
     *
     * @param integer $revisionId
     * @return AssignmentStage
     */
    public function setRevisionId($revisionId)
    {
        $this->revisionId = $revisionId;

        return $this;
    }

    /**
     * Get revisionId
     *
     * @return integer 
     */
    public function getRevisionId()
    {
        return $this->revisionId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->submissions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set assignment
     *
     * @param \Marca\AssignmentBundle\Entity\Assignment $assignment
     * @return AssignmentStage
     */
    public function setAssignment(\Marca\AssignmentBundle\Entity\Assignment $assignment = null)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment
     *
     * @return \Marca\AssignmentBundle\Entity\Assignment
     */
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * Set next
     *
     * @param \Marca\AssignmentBundle\Entity\AssignmentStage $next
     * @return AssignmentStage
     */
    public function setNext(\Marca\AssignmentBundle\Entity\AssignmentStage $next = null)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * Get next
     *
     * @return \Marca\AssignmentBundle\Entity\AssignmentStage
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set previous
     *
     * @param \Marca\AssignmentBundle\Entity\AssignmentStage $previous
     * @return AssignmentStage
     */
    public function setPrevious(\Marca\AssignmentBundle\Entity\AssignmentStage $previous = null)
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * Get previous
     *
     * @return \Marca\AssignmentBundle\Entity\AssignmentStage
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Add submissions
     *
     * @param \Marca\AssignmentBundle\Entity\AssignmentSubmission $submissions
     * @return AssignmentStage
     */
    public function addSubmission(\Marca\AssignmentBundle\Entity\AssignmentSubmission $submissions)
    {
        $this->submissions[] = $submissions;
        $submissions->setStage($this);
        return $this;
    }

    /**
     * Remove submissions
     *
     * @param \Marca\AssignmentBundle\AssignmentSubmission $submissions
     */
    public function removeSubmission(\Marca\AssignmentBundle\Entity\AssignmentSubmission $submissions)
    {
        $this->submissions->removeElement($submissions);
    }

    /**
     * Get submissions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubmissions()
    {
        return $this->submissions;
    }

    /**
     * Set reviewsRequired
     *
     * @param integer $reviewsRequired
     * @return AssignmentStage
     */
    public function setReviewsRequired($reviewsRequired)
    {
        $this->reviewsRequired = $reviewsRequired;

        return $this;
    }

    /**
     * Get reviewsRequired
     *
     * @return integer 
     */
    public function getReviewsRequired()
    {
        return $this->reviewsRequired;
    }

    /**
     * Set reviewInstructions
     *
     * @param string $reviewInstructions
     * @return AssignmentStage
     */
    public function setReviewInstructions($reviewInstructions)
    {
        $this->reviewInstructions = $reviewInstructions;

        return $this;
    }

    /**
     * Get reviewInstructions
     *
     * @return string 
     */
    public function getReviewInstructions()
    {
        return $this->reviewInstructions;
    }
}
