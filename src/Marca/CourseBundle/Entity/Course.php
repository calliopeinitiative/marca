<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Marca\CourseBundle\Entity\Course
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\CourseRepository")
 */
class Course
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
    * @ORM\ManyToOne(targetEntity="Term", inversedBy="course")
    */
    protected $term;

    /**
     * @var time $time
     *
     * @ORM\Column(name="time", type="time")
     */
    private $time;

    /**
     * @var integer $userid
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid;

    /**
     * @var boolean $enroll
     *
     * @ORM\Column(name="enroll", type="boolean")
     */
    private $enroll;

    /**
     * @var boolean $post
     *
     * @ORM\Column(name="post", type="boolean")
     */
    private $post;

    /**
     * @var integer $portRubricId
     *
     * @ORM\Column(name="portRubricId", type="integer")
     */
    private $portRubricId;

    /**
     * @var boolean $multicult
     *
     * @ORM\Column(name="multicult", type="boolean")
     */
    private $multicult;

    /**
     * @var integer $projectDefaultId
     *
     * @ORM\Column(name="projectDefaultId", type="integer")
     */
    private $projectDefaultId;

    /**
     * @var integer $parentId
     *
     * @ORM\Column(name="parentId", type="integer")
     */
    private $parentId;

    /**
     * @var integer $assessmentId
     *
     * @ORM\Column(name="assessmentId", type="integer")
     */
    private $assessmentId;

    /**
     * @var boolean $studentForum
     *
     * @ORM\Column(name="studentForum", type="boolean")
     */
    private $studentForum;

    /**
     * @var boolean $notes
     *
     * @ORM\Column(name="notes", type="boolean")
     */
    private $notes;

    /**
     * @var boolean $journal
     *
     * @ORM\Column(name="journal", type="boolean")
     */
    private $journal;

    /**
     * @var boolean $portfolio
     *
     * @ORM\Column(name="portfolio", type="boolean")
     */
    private $portfolio;

    /**
     * @var boolean $zine
     *
     * @ORM\Column(name="zine", type="boolean")
     */
    private $zine;

    /**
     * @var text $announcement
     *
     * @ORM\Column(name="announcement", type="text", nullable=true)
     */
    private $announcement;

    /**
     * @var boolean $portStatus
     *
     * @ORM\Column(name="portStatus", type="boolean")
     */
    private $portStatus;
  
    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="course")
     */
    protected $project;
    
    /**
     * @ORM\OneToMany(targetEntity="Roll", mappedBy="course")
     */
    protected $roll;

    public function __construct()
    {
        $this->roll = new ArrayCollection();
        $this->project = new ArrayCollection();
    } 
    

  

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
     * Set time
     *
     * @param time $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return time 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set enroll
     *
     * @param boolean $enroll
     */
    public function setEnroll($enroll)
    {
        $this->enroll = $enroll;
    }

    /**
     * Get enroll
     *
     * @return boolean 
     */
    public function getEnroll()
    {
        return $this->enroll;
    }

    /**
     * Set post
     *
     * @param boolean $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return boolean 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set portRubricId
     *
     * @param integer $portRubricId
     */
    public function setPortRubricId($portRubricId)
    {
        $this->portRubricId = $portRubricId;
    }

    /**
     * Get portRubricId
     *
     * @return integer 
     */
    public function getPortRubricId()
    {
        return $this->portRubricId;
    }

    /**
     * Set multicult
     *
     * @param boolean $multicult
     */
    public function setMulticult($multicult)
    {
        $this->multicult = $multicult;
    }

    /**
     * Get multicult
     *
     * @return boolean 
     */
    public function getMulticult()
    {
        return $this->multicult;
    }

    /**
     * Set projectDefaultId
     *
     * @param integer $projectDefaultId
     */
    public function setProjectDefaultId($projectDefaultId)
    {
        $this->projectDefaultId = $projectDefaultId;
    }

    /**
     * Get projectDefaultId
     *
     * @return integer 
     */
    public function getProjectDefaultId()
    {
        return $this->projectDefaultId;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set assessmentId
     *
     * @param integer $assessmentId
     */
    public function setAssessmentId($assessmentId)
    {
        $this->assessmentId = $assessmentId;
    }

    /**
     * Get assessmentId
     *
     * @return integer 
     */
    public function getAssessmentId()
    {
        return $this->assessmentId;
    }

    /**
     * Set studentForum
     *
     * @param boolean $studentForum
     */
    public function setStudentForum($studentForum)
    {
        $this->studentForum = $studentForum;
    }

    /**
     * Get studentForum
     *
     * @return boolean 
     */
    public function getStudentForum()
    {
        return $this->studentForum;
    }

    /**
     * Set notes
     *
     * @param boolean $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return boolean 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set journal
     *
     * @param boolean $journal
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
    }

    /**
     * Get journal
     *
     * @return boolean 
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * Set portfolio
     *
     * @param boolean $portfolio
     */
    public function setPortfolio($portfolio)
    {
        $this->portfolio = $portfolio;
    }

    /**
     * Get portfolio
     *
     * @return boolean 
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }

    /**
     * Set zine
     *
     * @param boolean $zine
     */
    public function setZine($zine)
    {
        $this->zine = $zine;
    }

    /**
     * Get zine
     *
     * @return boolean 
     */
    public function getZine()
    {
        return $this->zine;
    }

    /**
     * Set announcement
     *
     * @param text $announcement
     */
    public function setAnnouncement($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get announcement
     *
     * @return text 
     */
    public function getAnnouncement()
    {
        return $this->announcement;
    }

    /**
     * Set portStatus
     *
     * @param boolean $portStatus
     */
    public function setPortStatus($portStatus)
    {
        $this->portStatus = $portStatus;
    }

    /**
     * Get portStatus
     *
     * @return boolean 
     */
    public function getPortStatus()
    {
        return $this->portStatus;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set term
     *
     * @param Marca\CourseBundle\Entity\Term $term
     */
    public function setTerm(\Marca\CourseBundle\Entity\Term $term)
    {
        $this->term = $term;
    }

    /**
     * Get term
     *
     * @return Marca\CourseBundle\Entity\Term 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Add roll
     *
     * @param Marca\CourseBundle\Entity\Roll $roll
     */
    public function addRoll(\Marca\CourseBundle\Entity\Roll $roll)
    {
        $this->roll[] = $roll;
    }

    /**
     * Get roll
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRoll()
    {
        return $this->roll;
    }

    /**
     * Add project
     *
     * @param Marca\CourseBundle\Entity\Project $project
     */
    public function addProject(\Marca\CourseBundle\Entity\Project $project)
    {
        $this->project[] = $project;
    }

    /**
     * Get project
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProject()
    {
        return $this->project;
    }
}