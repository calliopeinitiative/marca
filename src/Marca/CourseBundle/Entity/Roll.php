<?php

namespace Marca\CourseBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
* Marca\CourseBundle\Entity\Roll
*
* @ORM\Table(name="roll")
* @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\RollRepository")
*/
class Roll
{
    const ROLE_PENDING=0;
    const ROLE_STUDENT=1;
    const ROLE_INSTRUCTOR=2;
    const ROLE_TA=3;
    const ROLE_PORTREVIEWER=4;
    
    /**
* @var integer $id
*
* @ORM\Column(name="id", type="integer")
* @ORM\Id
* @ORM\GeneratedValue(strategy="AUTO")
*/
    private $id;
    
    /**
* @var integer $role
*
* @ORM\Column(name="role", type="integer")
*/
    private $role;

    /**
* @var integer $status
*
* @ORM\Column(name="status", type="integer")
*/
    private $status = 0;

    /**
    * @ORM\ManyToOne(targetEntity="Course", inversedBy="roll")
    * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
    */
    protected $course;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="roll")
    */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Marca\GradebookBundle\Entity\Attendance", mappedBy="roll")
     */
    protected $attendance;

    /**
     *@ORM\ManyToMany(targetEntity="Marca\CourseBundle\Entity\Team") 
     */
    protected $teams;

    /**
     * @ORM\OneToOne(targetEntity="Marca\AssignmentBundle\Entity\Assignment")
     * @ORM\JoinColumn(name="current_assignment_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $current_assignment;
    
    public function __construct(){
        $this->teams = new ArrayCollection();
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
* Set role
*
* @param integer $role
*/
    public function setRole($role)
    {
        $this->role = $role;
    }
    
    
    /**
* Get role
*
* @return integer
*/
    public function getRole()
    {
        return $this->role;
    }

    /**
* Set status
*
* @param integer $status
*/
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
* Get status
*
* @return integer
*/
    public function getStatus()
    {
        return $this->status;
    }

    /**
* Set course
*
* @param \Marca\CourseBundle\Entity\Course $course
*/
    public function setCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course = $course;
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
* Set user
*
* @param \Marca\UserBundle\Entity\User $user
*/
    public function setUser(\Marca\UserBundle\Entity\User $user)
    {
        $this->user = $user;
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
     *Get firstname
     * @return string 
     */
    public function getfirstname()
    {
        return $this->user->getFirstname();
    }

     /**
     *Get lastname
     * @return string 
     */
    public function getlastname()
    {
        return $this->user->getLastname();

    }
    
    /**Get full name
     * @return string
     */
    public function getfullname()
    {
        
        return $this->getfirstname() . " " . $this->getlastname();
        
    }
    
    
    /**
     * Add teams
     *
     * @param \Marca\CourseBundle\Team $teams
     */
    public function addTeam(\Marca\CourseBundle\Entity\Team $team)
    {
        $this->teams[] = $team;
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }
    

    /**
     * Remove teams
     *
     * @param \Marca\CourseBundle\Entity\Team $teams
     */
    public function removeTeam(\Marca\CourseBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }
    

    /**
     * Add attendance
     *
     * @param \Marca\GradebookBundle\Entity\Attendance $attendance
     * @return Roll
     */
    public function addAttendance(\Marca\GradebookBundle\Entity\Attendance $attendance)
    {
        $this->attendance[] = $attendance;

        return $this;
    }

    /**
     * Remove attendance
     *
     * @param \Marca\GradebookBundle\Entity\Attendance $attendance
     */
    public function removeAttendance(\Marca\GradebookBundle\Entity\Attendance $attendance)
    {
        $this->attendance->removeElement($attendance);
    }

    /**
     * Get attendance
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set current_assignment
     *
     * @param \Marca\AssignmentBundle\Entity\Assignment $currentAssignment
     * @return Roll
     */
    public function setCurrentAssignment(\Marca\AssignmentBundle\Entity\Assignment $currentAssignment = null)
    {
        $this->current_assignment = $currentAssignment;

        return $this;
    }

    /**
     * Get current_assignment
     *
     * @return \Marca\AssignmentBundle\Entity\Assignment 
     */
    public function getCurrentAssignment()
    {
        return $this->current_assignment;
    }
}
