<?php
// src/Marca/UserBundle/Entity/User.php

namespace Marca\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use FR3D\LdapBundle\Model\LdapUserInterface;

/**
 * @ORM\Entity(repositoryClass="Marca\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="marca_user")
 */
class User extends BaseUser implements LdapUserInterface
{
     /**
     * Ldap Object Distinguished Name
     * @var string $dn
     */
    private $dn;

    /**
     * {@inheritDoc}
     */
    public function setDn($dn)
    {
        $this->dn = $dn;
    }

    /**
     * {@inheritDoc}
     */
    public function getDn()
    {
        return $this->dn;
    }
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
       
    /**
     * @var string $lastname
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string $firstname
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    private $firstname;
    
    /**
     * @var string $photo
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;    

    /**
     * @var string $bio
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio = '<p></p>';      
    
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
     * @ORM\ManyToMany(targetEntity="Marca\DocBundle\Entity\Markupset", cascade={"persist"})
     * @ORM\JoinTable(name="user_markupset")
     */
    protected $markupsets; 
    
    /**
     * @ORM\Column(name="share_email", type="boolean", nullable=true)
     */
    private $share_email = false;
    
    public function __construct()
    {
        parent::__construct();
        $this->roll = new ArrayCollection();
        $this->course = new ArrayCollection();
        $this->markupsets = new ArrayCollection();
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

    /**
     * Set photo
     *
     * @param string $photo
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return User
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    
        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
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
     * Remove roll
     *
     * @param Marca\CourseBundle\Entity\Roll $roll
     */
    public function removeRoll(\Marca\CourseBundle\Entity\Roll $roll)
    {
        $this->roll->removeElement($roll);
    }

    /**
     * Remove markup
     *
     * @param Marca\DocBundle\Entity\Markup $markup
     */
    public function removeMarkup(\Marca\DocBundle\Entity\Markup $markup)
    {
        $this->markup->removeElement($markup);
    }

    /**
     * Remove file
     *
     * @param Marca\FileBundle\Entity\File $file
     */
    public function removeFile(\Marca\FileBundle\Entity\File $file)
    {
        $this->file->removeElement($file);
    }

    /**
     * Remove journal
     *
     * @param Marca\JournalBundle\Entity\Journal $journal
     */
    public function removeJournal(\Marca\JournalBundle\Entity\Journal $journal)
    {
        $this->journal->removeElement($journal);
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
     * Remove tagset
     *
     * @param Marca\TagBundle\Entity\Tagset $tagset
     */
    public function removeTagset(\Marca\TagBundle\Entity\Tagset $tagset)
    {
        $this->tagset->removeElement($tagset);
    }

    /**
     * Add markupsets
     *
     * @param Marca\DocBundle\Entity\MarkupSet $markupsets
     * @return User
     */
    public function addMarkupset(\Marca\DocBundle\Entity\MarkupSet $markupsets)
    {
        $this->markupsets[] = $markupsets;
    
        return $this;
    }

    /**
     * Remove markupsets
     *
     * @param Marca\DocBundle\Entity\MarkupSet $markupsets
     */
    public function removeMarkupset(\Marca\DocBundle\Entity\MarkupSet $markupsets)
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
        $markupsets_array = $this->markupsets->toArray();
        usort($markupsets_array, array("Marca\DocBundle\Entity\Markupset","cmp_shared"));
        return $markupsets_array;
        
        //return $this->markupsets;
    }

}