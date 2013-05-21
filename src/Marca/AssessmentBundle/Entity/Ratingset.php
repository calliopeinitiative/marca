<?php

namespace Marca\AssessmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Ratingset
 *
 * @ORM\Table(name="ratingset")
 * @ORM\Entity(repositoryClass="Marca\AssessmentBundle\Entity\RatingsetRepository")
 */
class Ratingset
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
    private $grade = 0;    
    
    /**
     * @var text $notesforstudent
     *
     * @ORM\Column(name="notesforstudent", type="text", nullable=true)
     */
    private $notesforstudent;

     /**
     * @var text $notesforreviewer
     *
     * @ORM\Column(name="notesforreviewer", type="text", nullable=true)
     */
    private $notesforreviewer;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $user;    
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course")
    */
    protected $course;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $rater; 
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\PortfolioBundle\Entity\Portfolioset", inversedBy="ratingsets")
    */
    protected $portfolioset; 
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\AssessmentBundle\Entity\Rating", mappedBy="ratingset", cascade={"remove", "persist"})
    */
    protected $ratings;     
    
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
     * @return Ratingset
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
     * @return Ratingset
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
     * Set user
     *
     * @param \Marca\UserBundle\Entity\User $user
     * @return Ratingset
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
     * @return Ratingset
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
     * Add ratings
     *
     * @param \Marca\AssessmentBundle\Entity\Rating $ratings
     * @return Ratingset
     */
    public function addRating(\Marca\AssessmentBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;
    
        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \Marca\AssessmentBundle\Entity\Rating $ratings
     */
    public function removeRating(\Marca\AssessmentBundle\Entity\Rating $ratings)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * Set rater
     *
     * @param \Marca\UserBundle\Entity\User $rater
     * @return Ratingset
     */
    public function setRater(\Marca\UserBundle\Entity\User $rater = null)
    {
        $this->rater = $rater;
    
        return $this;
    }

    /**
     * Get rater
     *
     * @return \Marca\UserBundle\Entity\User 
     */
    public function getRater()
    {
        return $this->rater;
    }

    /**
     * Set portfolioset
     *
     * @param \Marca\PortfolioBundle\Entity\Portfolioset $portfolioset
     * @return Ratingset
     */
    public function setPortfolioset(\Marca\PortfolioBundle\Entity\Portfolioset $portfolioset = null)
    {
        $this->portfolioset = $portfolioset;
    
        return $this;
    }

    /**
     * Get portfolioset
     *
     * @return \Marca\PortfolioBundle\Entity\Portfolioset 
     */
    public function getPortfolioset()
    {
        return $this->portfolioset;
    }

    /**
     * Set grade
     *
     * @param integer $grade
     * @return Ratingset
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
     * Set notesforstudent
     *
     * @param string $notesforstudent
     * @return Ratingset
     */
    public function setNotesforstudent($notesforstudent)
    {
        $this->notesforstudent = $notesforstudent;
    
        return $this;
    }

    /**
     * Get notesforstudent
     *
     * @return string 
     */
    public function getNotesforstudent()
    {
        return $this->notesforstudent;
    }

    /**
     * Set notesforreviewer
     *
     * @param string $notesforreviewer
     * @return Ratingset
     */
    public function setNotesforreviewer($notesforreviewer)
    {
        $this->notesforreviewer = $notesforreviewer;
    
        return $this;
    }

    /**
     * Get notesforreviewer
     *
     * @return string 
     */
    public function getNotesforreviewer()
    {
        return $this->notesforreviewer;
    }
    
    public function isOwner($rater)
    {
        if($rater == $this->rater){
            return true;
        }
        else{
            return false;
        }
    }    
}