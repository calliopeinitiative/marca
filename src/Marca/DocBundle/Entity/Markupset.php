<?php

namespace Marca\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\DocBundle\Entity\Markupset
 *
 * @ORM\Table(name="markupset")
 * @ORM\Entity(repositoryClass="Marca\DocBundle\Entity\MarkupsetRepository")
 */
class Markupset
{
    
    const LOCAL=0;
    const SHARED=1;
    
    
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
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description; 

    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $owner;
    
    /**
     * Users who access this markupset
     * @ORM\ManyToMany(targetEntity="Marca\UserBundle\Entity\User")
     *
     */
    protected $users;
       
   /**
    * @ORM\ManyToMany(targetEntity="Marca\DocBundle\Entity\Markup", mappedBy="markupset")
    **/
    protected $markup; 
    
    /**
     * @var integer $shared
     *
     * @ORM\Column(name="shared", type="integer", nullable=true)
     */
    private $shared=0;   
    
    /**
     * @var integer $sortorder
     *
     * @ORM\Column(name="sortorder", type="integer", nullable=true)
     */
    private $sortorder=0;      
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="Marca\CourseBundle\Entity\Course", mappedBy="markupsets")
     *  
    **/
    protected $courses;
    
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
     * @return Markupset
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
     * Constructor
     */
    public function __construct()
    {
        $this->markup = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set shared
     *
     * @param integer $shared
     * @return Markupset
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
     * Set user
     *
     * @param \Marca\UserBundle\Entity\User $user
     * @return Markupset
     */
    public function setOwner(\Marca\UserBundle\Entity\User $user = null)
    {
        $this->owner = $user;
        $this->addUser($user);
        return $this;
    }

    /**
     * Get user
     *
     * @return \Marca\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add markup
     *
     * @param \Marca\DocBundle\Entity\Markup $markup
     * @return Markupset
     */
    public function addMarkup(\Marca\DocBundle\Entity\Markup $markup)
    {
        $this->markup[] = $markup;
    
        return $this;
    }

    /**
     * Remove markup
     *
     * @param \Marca\DocBundle\Entity\Markup $markup
     */
    public function removeMarkup(\Marca\DocBundle\Entity\Markup $markup)
    {
        $this->markup->removeElement($markup);
    }

    /**
     * Get markup
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarkup()
    {
        return $this->markup;
    }

    /**
     * Add course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     * @return Markupset
     */
    public function addCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->courses[] = $course;
    
        return $this;
    }

    /**
     * Remove course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     */
    public function removeCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->courses->removeElement($course);
    }

    /**
     * Get course
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourses()
    {
        return $this->courses;
    }
    
    public function isOwner($user)
    {
        if($user == $this->owner){
            return true;
        }
        else{
            return false;
        }
    }  
    

    /**
     * Add users
     *
     * @param \Marca\UserBundle\Entity\User $users
     * @return Markupset
     */
    public function addUser(\Marca\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
        $users->addMarkupset($this);
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Marca\UserBundle\Entity\User $users
     */
    public function removeUser(\Marca\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
        $users->removeMarkupset($this);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Markupset
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
    
    public function hasAccess($user)
    {
       $users_array = $this->users->toArray();
       if(in_array($user, $users_array)){
           return true;
       }
       else{
           return false;
        }
    }
    
    //function for comparing and sorting projects by sortOrder
    static function cmp_shared($a, $b)
    {
        if($a->shared === $b->shared) {
            return 0;
        }
        return($a->shared > $b->shared) ? +1 : -1;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Markupset
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
    
        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer 
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }
}
