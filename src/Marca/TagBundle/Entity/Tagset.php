<?php

namespace Marca\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Marca\TagBundle\Entity\Tagset
 *
 * @ORM\Table(name="tagset")
 * @ORM\Entity(repositoryClass="Marca\TagBundle\Entity\TagsetRepository")
 */
class Tagset
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
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="tagset")
    */
    protected $user;   
    
    
   /**
    * @ORM\ManyToMany(targetEntity="Marca\TagBundle\Entity\Tag", mappedBy="tagset")
    */
    protected $tag; 
    
    /**
     * @var integer $shared
     *
     * @ORM\Column(name="shared", type="integer", nullable=true)
     */
    private $shared=0;      
    
    /**
     * Inverse Side
     *
     * @ORM\ManyToMany(targetEntity="Marca\CourseBundle\Entity\Course", mappedBy="tagset")
    */
    private $course;
     
    
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

    public function __construct()
    {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add tag
     *
     * @param Marca\TagBundle\Entity\Tag $tag
     */
    public function addTag(\Marca\TagBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;
    }

    /**
     * Get tag
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Add course
     *
     * @param Marca\TagBundle\Entity\Course $course
     */
    public function addCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course[] = $course;
    }

    /**
     * Get course
     *
     * @return Doctrine\Common\Collections\Collection 
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
     * Remove tag
     *
     * @param Marca\TagBundle\Entity\Tag $tag
     */
    public function removeTag(\Marca\TagBundle\Entity\Tag $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Remove course
     *
     * @param Marca\CourseBundle\Entity\Course $course
     */
    public function removeCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course->removeElement($course);
    }

    /**
     * Set shared
     *
     * @param integer $shared
     * @return Tagset
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
    
    public function isOwner($user)
    {
        if($user == $this->user){
            return true;
        }
        else{
            return false;
        }
    }    
}