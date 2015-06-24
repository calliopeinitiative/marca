<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssignmentSubmission
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\AssignmentSubmissionRepository")
 */
class AssignmentSubmission
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
     * @var \DateTime
     *
     * @ORM\Column(name="submitted", type="datetime")
     */
    private $submitted;

    /**
     * @var integer
     *
     * @ORM\Column(name="etherpadrev", type="integer")
     */
    private $etherpadrev;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="asssignmentSubmissions")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\FileBundle\Entity\File", inversedBy="assignmentSubmissions")
     */
    protected $file;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\AssignmentBundle\Entity\AssignmentStage", inversedBy="submissions")
     */
    protected $stage;

    /**
     * @ORM\OneToMany(targetEntity="Marca\AssignmentBundle\Entity\Review", mappedBy="assignmentSubmission")
     */
    protected $reviews;


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
     * Set submitted
     *
     * @param \DateTime $submitted
     * @return AssignmentSubmission
     */
    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;

        return $this;
    }

    /**
     * Get submitted
     *
     * @return \DateTime 
     */
    public function getSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Set etherpadrev
     *
     * @param integer $etherpadrev
     * @return AssignmentSubmission
     */
    public function setEtherpadrev($etherpadrev)
    {
        $this->etherpadrev = $etherpadrev;

        return $this;
    }

    /**
     * Get etherpadrev
     *
     * @return integer 
     */
    public function getEtherpadrev()
    {
        return $this->etherpadrev;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Marca\UserBundle\User $user
     * @return AssignmentSubmission
     */
    public function setUser(\Marca\UserBundle\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Marca\UserBundle\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set file
     *
     * @param \Marca\FileBundle\File $file
     * @return AssignmentSubmission
     */
    public function setFile(\Marca\FileBundle\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Marca\FileBundle\File 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set stage
     *
     * @param \Marca\AssignmentBundle\AssignmentStage $stage
     * @return AssignmentSubmission
     */
    public function setStage(\Marca\AssignmentBundle\AssignmentStage $stage = null)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get stage
     *
     * @return \Marca\AssignmentBundle\AssignmentStage 
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Add reviews
     *
     * @param \Marca\AssignmentBundle\Review $reviews
     * @return AssignmentSubmission
     */
    public function addReview(\Marca\AssignmentBundle\Review $reviews)
    {
        $this->reviews[] = $reviews;

        return $this;
    }

    /**
     * Remove reviews
     *
     * @param \Marca\AssignmentBundle\Review $reviews
     */
    public function removeReview(\Marca\AssignmentBundle\Review $reviews)
    {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviews()
    {
        return $this->reviews;
    }
}
