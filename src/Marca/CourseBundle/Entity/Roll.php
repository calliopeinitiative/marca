<?php

namespace Marca\CourseBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
* Marca\CourseBundle\Entity\Roll
*
* @ORM\Table()
* @ORM\Entity(repositoryClass="Marca\CourseBundle\Entity\RollRepository")
*/
class Roll
{
    const ROLE_PENDING=0;
    const ROLE_STUDENT=1;
    const ROLE_INSTRUCTOR=2;
    const ROLE_TA=3;
    
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
     *@ORM\ManyToMany(targetEntity="Marca\CourseBundle\Entity\Team", mappedBy = "roll") 
     */
    protected $teams;
    
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
* @param Marca\CourseBundle\Entity\Course $course
*/
    public function setCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course = $course;
    }

    /**
* Get course
*
* @return Marca\CourseBundle\Entity\Course
*/
    public function getCourse()
    {
        return $this->course;
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
     * @param Marca\CourseBundle\Team $teams
     */
    public function addTeam(\Marca\CourseBundle\Team $team)
    {
        $this->teams[] = $team;
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
}