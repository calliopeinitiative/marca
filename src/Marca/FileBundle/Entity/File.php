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
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="file")
    */
    protected $user;    

    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Project", inversedBy="file")
    */
    protected $project;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course", inversedBy="file")
    */
    protected $course;    
    
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
    private $access = 0;   
    
    /**
     * @ORM\OneToOne(targetEntity="Marca\DocBundle\Entity\Doc", mappedBy="file")
     * 
     */
    private $doc; 
    
    
   /**
    * @ORM\ManyToMany(targetEntity="Marca\TagBundle\Entity\Tag")
    */
    protected $tag;  
    
   /**
    * @ORM\ManyToMany(targetEntity="Marca\PortfolioBundle\Entity\Portitem", inversedBy="file")
    */
    protected $portitem;  

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
        $courseid = $this->getCourse()->getId();
        $userid = $this->getUser()->getId();
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
     * Add portitem
     *
     * @param Marca\PortfolioBundle\Entity\Portitem $portitem
     */
    public function addPortitem(\Marca\PortfolioBundle\Entity\Portitem $portitem)
    {
        $this->portitem[] = $portitem;
    }

    /**
     * Get portitem
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPortitem()
    {
        return $this->portitem;
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