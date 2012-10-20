<?php
// src/Marca/UserBundle/Entity/User.php

namespace Marca\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="marca_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
       
    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

 
     /**
     * @ORM\OneToMany(targetEntity="Marca\CourseBundle\Entity\Course", mappedBy="user")
     */
    protected $course;
    
    
     /**
     * @ORM\OneToMany(targetEntity="Marca\CourseBundle\Entity\Roll", mappedBy="user")
     */
    protected $roll;
    
     /**
     * @ORM\OneToMany(targetEntity="Marca\DocBundle\Entity\Markup", mappedBy="user")
     */
    protected $markup;    
  
    /**
     * @ORM\OneToMany(targetEntity="Marca\FileBundle\Entity\File", mappedBy="user")
     */
    protected $file;
    
    /**
     * @ORM\OneToMany(targetEntity="Marca\JournalBundle\Entity\Journal", mappedBy="user")
     */
    protected $journal; 
    
    /**
     * @ORM\OneToMany(targetEntity="Marca\TagBundle\Entity\Tag", mappedBy="user")
     */
    protected $tag; 
    
    /**
     * @ORM\OneToMany(targetEntity="Marca\TagBundle\Entity\Tagset", mappedBy="user")
     */
    protected $tagset;  
    
    /**
     * @ORM\Column(name="share_email", type="boolean")
     */
    private $share_email = false;
    
    public function __construct()
    {
        parent::__construct();
        $this->roll = new ArrayCollection();
        $this->course = new ArrayCollection();
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
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
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
     * Add roll
     *
     * @param Marca\CourseBundle\Entity\Course $course
     */
    public function addCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course[] = $course;
    }

    /**
     * Get roll
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCourse()
    {
        return $this->course;
    }    
    

    /**
     * Add markup
     *
     * @param Marca\DocBundle\Entity\Markup $markup
     */
    public function addMarkup(\Marca\DocBundle\Entity\Markup $markup)
    {
        $this->markup[] = $markup;
    }

    /**
     * Get markup
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMarkup()
    {
        return $this->markup;
    }

    /**
     * Add file
     *
     * @param Marca\FileBundle\Entity\File $file
     */
    public function addFile(\Marca\FileBundle\Entity\File $file)
    {
        $this->file[] = $file;
    }

    /**
     * Get file
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Add journal
     *
     * @param Marca\JournalBundle\Entity\Journal $journal
     */
    public function addJournal(\Marca\JournalBundle\Entity\Journal $journal)
    {
        $this->journal[] = $journal;
    }

    /**
     * Get journal
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getJournal()
    {
        return $this->journal;
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
     * Get share email
     * 
     * @return boolean
     */
    public function getShareEmail()
    {
        return $this->share_email;
    }
    
    /**
     * Set share email
     * 
     * @param boolean
     */
    public function setShareEmail($share_email)
    {
        $this->share_email = $share_email;
    }
}