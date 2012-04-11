<?php

namespace Marca\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Marca\FileBundle\Entity\File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\FileBundle\Entity\FileRepository")
 */
class File
{
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        $courseid = $this->getCourseid();
        $userid = $this->getUserid();
        return 'uploads/files'.'/'.$courseid.'/'.$userid;
    }
    
     /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->path = $this->file->getClientOriginalName();
        }
    }
    
     /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */     
    public function upload()
    {
    // the file property can be empty if the field is not required
    if (null === $this->file) {
        return;
    }

    // we use the original file name here but you should
    // sanitize it at least to avoid any security issues

    // move takes the target directory and then the target filename to move to
    $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

    // set the path property to the filename where you'ved saved the file
    $this->path = $this->file->getClientOriginalName();
    
    // set the name property to the filename where you'ved saved the file
    $this->name = $this->file->getClientOriginalName();    

    // clean up the file property as you won't need it anymore
    $this->file = null;
    }  
 
     /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
    
    /**
     * Return the contents of the file.
     * 
     * @return string
     */
    public function getContents()
    {
        return file_get_contents( $this->getAbsolutePath() );
    }
    
    /**
     * Return the extention of the file.
     * 
     * @return string
     */
    public function getExt()
    {
        $filename = $this->getPath(); 
        return pathinfo($filename, PATHINFO_EXTENSION);
    }   

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer $userid
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid;

    /**
     * @var integer $courseid
     *
     * @ORM\Column(name="courseid", type="integer", nullable=true)
     */
    private $courseid;


    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Project", inversedBy="file")
    */
    protected $project;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
     
     /**
     * @var integer $access
     *
     * @ORM\Column(name="access", type="integer", nullable=true)
     */
    private $access;   
    
    /**
     * @ORM\OneToOne(targetEntity="Marca\DocBundle\Entity\Doc", mappedBy="file")
     * 
     */
    private $doc; 
    
    /**
     * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\Profile", inversedBy="file")
     * @ORM\JoinColumn(name="userid", referencedColumnName="id")
     * 
     */
    private $profile; 
    
   /**
    * @ORM\ManyToMany(targetEntity="Marca\TagBundle\Entity\Tag")
    */
    protected $tag;     

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
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
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
     * Set courseid
     *
     * @param integer $courseid
     */
    public function setCourseid($courseid)
    {
        $this->courseid = $courseid;
    }

    /**
     * Get courseid
     *
     * @return integer 
     */
    public function getCourseid()
    {
        return $this->courseid;
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
     * Set project
     *
     * @param Marca\CourseBundle\Entity\Project $project
     */
    public function setProject(\Marca\CourseBundle\Entity\Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return Marca\CourseBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set doc
     *
     * @param Marca\DocBundle\Entity\Doc $doc
     */
    public function setDoc(\Marca\DocBundle\Entity\Doc $doc)
    {
        $this->doc = $doc;
    }

    /**
     * Get doc
     *
     * @return Marca\DocBundle\Entity\Doc 
     */
    public function getDoc()
    {
        return $this->doc;
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
     * Set access
     *
     * @param integer $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }

    /**
     * Get access
     *
     * @return integer 
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set profile
     *
     * @param Marca\UserBundle\Entity\Profile $profile
     */
    public function setProfile(\Marca\UserBundle\Entity\Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @return Marca\UserBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}