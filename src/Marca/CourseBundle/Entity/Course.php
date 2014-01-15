<?php

namespace Marca\CourseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Marca\CourseBundle\Entity\Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\CourseRepository")
 */
class Course
{
    const MODULE_NO=0;
    const MODULE_YES=1;
    const MODULE_SHARED=2;
    
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
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Term", cascade={"persist"})
     * @ORM\JoinColumn(name="term_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $term;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="course")
    */
    protected $user;    

    /**
     * @var time $time
     * @Assert\Time()
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="time", type="time")
     */
    private $time;

    /**
     * @var boolean $enroll
     *
     * @ORM\Column(name="enroll", type="boolean")
     */
    private $enroll = true;

    /**
     * @var boolean $post
     *
     * @ORM\Column(name="post", type="boolean")
     */
    private $post = true;
   
    /**
    * @ORM\ManyToOne(targetEntity="Marca\PortfolioBundle\Entity\Portset", inversedBy="course")
    */
    protected $portset;  
    
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Assessmentset", inversedBy="course")
    */
    protected $assessmentset; 
    

    /**
     * @var boolean $studentForum
     *
     * @ORM\Column(name="studentForum", type="boolean")
     */
    private $studentForum = false;

    /**
     * @var boolean $notes
     *
     * @ORM\Column(name="notes", type="boolean", nullable=true)
     */
    private $notes = true;
    
    /**
     * @var boolean $forum
     *
     * @ORM\Column(name="forum", type="boolean")
     */
    private $forum = true;    

    /**
     * @var boolean $journal
     *
     * @ORM\Column(name="journal", type="boolean")
     */
    private $journal = true;

    /**
     * @var boolean $portfolio
     *
     * @ORM\Column(name="portfolio", type="boolean")
     */
    private $portfolio = true;

    /**
     * @var boolean $zine
     *
     * @ORM\Column(name="zine", type="boolean", nullable=true)
     */
    private $zine = false;

    /**
     * @var text $announcement
     *
     * @ORM\Column(name="announcement", type="text", nullable=true)
     */
    private $announcement = 'Welcome';

    /**
     * @var boolean $portStatus
     *
     * @ORM\Column(name="portStatus", type="boolean")
     */
    private $portStatus = true;
    
    /**
     * @ORM\OneToOne(targetEntity="Marca\CourseBundle\Entity\Project")
     * @ORM\JoinColumn(name="projectDefault_id", onDelete="CASCADE")
     * 
     */
    private $projectDefault;     
  
    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="course")
     */
    protected $projects;
    
    /**
     * @ORM\OneToMany(targetEntity="Roll", mappedBy="course")
     */
    protected $roll;
    
    /**
     *@ORM\OneToMany(targetEntity="Team", mappedBy="course") 
     */
    protected $teams;
    
    /**
     * @ORM\ManyToMany(targetEntity="Course")
     * @ORM\JoinTable(name="parent_child",
     *      joinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")}
     *      )
     */
    private $parents;
    
    /**
     * Inverse Side
     *
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "You must specify at least one markup set."
     * )
     * @ORM\ManyToMany(targetEntity="Marca\DocBundle\Entity\Markupset", inversedBy="courses")
     * @ORM\OrderBy({"sortorder" = "ASC"})
    **/
    protected $markupsets;

    /**
     * @var boolean $pendingFlag
     * @ORM\Column(name="pendingFlag", type="boolean")
     */
    private $pendingFlag = false; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Marca\AdminBundle\Entity\Institution", inversedBy="courses", cascade={"detach"})
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $institution; 
    
    /**
     * @var boolean $module
     *
     * @ORM\Column(name="module", type="integer", nullable=true)
     */
    private $module = 0;    
    
    /**
     * @ORM\ManyToMany(targetEntity="Marca\AssignmentBundle\Entity\ReviewRubric")
     */
    private $reviewrubrics;
    
    public function __construct()
    {
        $this->roll = new ArrayCollection();
        $this->tagset = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->projectDefault = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->markupsets = new ArrayCollection();
    } 
    
   /**
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "You must specify at least one set of labels."
     * )
     * @ORM\ManyToMany(targetEntity="Marca\TagBundle\Entity\Tagset", inversedBy="course")
     * @ORM\JoinTable(name="course_tagset",
     *      joinColumns={@ORM\JoinColumn(name="Course_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="Tagset_id", referencedColumnName="id")})
    */
    protected $tagset; 

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
     * Set user
     *
     * @param Marca\UserBundle\Entity\User $user
     */
    public function setUser(\Marca\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Marca\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
        $this->projects[] = $project;
    }

    /**
     * Get project
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set forum
     *
     * @param boolean $forum
     */
    public function setForum($forum)
    {
        $this->forum = $forum;
    }

    /**
     * Get forum
     *
     * @return boolean 
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Add tagset
     *
     * @param Marca\TagBundle\Entity\Tagset $tagset
     */
    public function addTagset(\Marca\TagBundle\Entity\Tagset $tagset)
    {
        $this->tagset[] = $tagset;
    }

    /**
     * Get tagset
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTagset()
    {
        return $this->tagset;
    }

    /**
     * Set projectDefault
     *
     * @param Marca\CourseBundle\Entity\Project $projectDefault
     */
    public function setProjectDefault(\Marca\CourseBundle\Entity\Project $projectDefault)
    {
        $this->projectDefault = $projectDefault;
    }

    /**
     * Get projectDefault
     *
     * @return Marca\CourseBundle\Entity\Project 
     */
    public function getProjectDefault()
    {
        return $this->projectDefault;
    }
    
    /**
     *Get projectsInSortOrder - returns the course's projects in sort order
     * @return array 
     */
    public function getProjectsInSortOrder()
    {
        $project_array = $this->projects->toArray();
        usort($project_array, array("Marca\CourseBundle\Entity\Project","cmp_sortOrder"));
        return $project_array;
    }

    /**
     * Set portset
     *
     * @param Marca\PortfolioBundle\Entity\Portset $portset
     */
    public function setPortset(\Marca\PortfolioBundle\Entity\Portset $portset)
    {
        $this->portset = $portset;
    }

    /**
     * Get portSet
     *
     * @return Marca\PortfolioBundle\Entity\Portset 
     */
    public function getPortset()
    {
        return $this->portset;
    }

    /**
     * Add teams
     *
     * @param Marca\CourseBundle\Entity\Team $teams
     */
    public function addTeam(\Marca\CourseBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;
    }

    /**
     * Get teams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }
    
    /**
     * Set Pending Flag
     * 
     * @param boolean $flag 
     */
    public function setPendingFlag($flag)
    {
        $this->pendingFlag = $flag;
    }
    
    /**
     * Return Pending Status
     * 
     * @return boolean 
     */
    public function hasPendingStudents()
    {
        return $this->pendingFlag;
    }
    
    public function isOwner($user)
    {
        if($user == $this->user){
            return true;
        }
        else{
            return false;
        }
    }    
    

    /**
     * Get pendingFlag
     *
     * @return boolean 
     */
    public function getPendingFlag()
    {
        return $this->pendingFlag;
    }

    /**
     * Remove projects
     *
     * @param Marca\CourseBundle\Entity\Project $projects
     */
    public function removeProject(\Marca\CourseBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Remove roll
     *
     * @param Marca\CourseBundle\Entity\Roll $roll
     */
    public function removeRoll(\Marca\CourseBundle\Entity\Roll $roll)
    {
        $this->roll->removeElement($roll);
    }

    /**
     * Remove teams
     *
     * @param Marca\CourseBundle\Entity\Team $teams
     */
    public function removeTeam(\Marca\CourseBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Remove tagset
     *
     * @param Marca\TagBundle\Entity\Tagset $tagset
     */
    public function removeTagset(\Marca\TagBundle\Entity\Tagset $tagset)
    {
        $this->tagset->removeElement($tagset);
    }



    /**
     * Add parents
     *
     * @param Marca\CourseBundle\Entity\Course $parents
     * @return Course
     */
    public function addParent(\Marca\CourseBundle\Entity\Course $parents)
    {
        $this->parents[] = $parents;
    
        return $this;
    }

    /**
     * Remove parents
     *
     * @param Marca\CourseBundle\Entity\Course $parents
     */
    public function removeParent(\Marca\CourseBundle\Entity\Course $parents)
    {
        $this->parents->removeElement($parents);
    }

    /**
     * Get parents
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add markupsets
     *
     * @param Marca\DocBundle\Entity\Markupset $markupsets
     * @return Course
     */
    public function addMarkupset(\Marca\DocBundle\Entity\Markupset $markupsets)
    {
        $this->markupsets[] = $markupsets;
    
        return $this;
    }

    /**
     * Remove markupsets
     *
     * @param Marca\DocBundle\Entity\Markupset $markupsets
     */
    public function removeMarkupset(\Marca\DocBundle\Entity\Markupset $markupsets)
    {
        $this->markupsets->removeElement($markupsets);
    }

    /**
     * Get markupsets
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMarkupsets()
    {
        return $this->markupsets;
    }

    /**
     * Set assessmentset
     *
     * @param \Marca\AssessmentBundle\Entity\Assessmentset $assessmentset
     * @return Course
     */
    public function setAssessmentset(\Marca\AssessmentBundle\Entity\Assessmentset $assessmentset = null)
    {
        $this->assessmentset = $assessmentset;
    
        return $this;
    }

    /**
     * Get assessmentset
     *
     * @return \Marca\AssessmentBundle\Entity\Assessmentset 
     */
    public function getAssessmentset()
    {
        return $this->assessmentset;
    }

    /**
     * Set institution
     *
     * @param \Marca\AdminBundle\Entity\Institution $institution
     * @return Course
     */
    public function setInstitution(\Marca\AdminBundle\Entity\Institution $institution = null)
    {
        $this->institution = $institution;
    
        return $this;
    }

    /**
     * Get institution
     *
     * @return \Marca\AdminBundle\Entity\Institution 
     */
    public function getInstitution()
    {
        return $this->institution;
    }



    /**
     * Set module
     *
     * @param integer $module
     * @return Course
     */
    public function setModule($module)
    {
        $this->module = $module;
    
        return $this;
    }

    /**
     * Get module
     *
     * @return integer 
     */
    public function getModule()
    {
        return $this->module;
    }
}