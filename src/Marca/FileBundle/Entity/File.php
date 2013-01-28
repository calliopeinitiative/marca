<?php

namespace Marca\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Marca\FileBundle\Entity\File
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\FileBundle\Entity\FileRepository")
 * @Vich\Uploadable
 */
class File
{

     /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"application/vnd.ms-office", "image/png", "image/jpeg", "image/pjpeg", "application/pdf", "application/vnd.oasis.opendocument.text", "application/vnd.oasis.opendocument.presentation","application/vnd.oasis.opendocument.spreadsheet", "application/msword", "application/mspowerpoint", "application/excel", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/zip"}
     * )
     * @Vich\UploadableField(mapping="property_file", fileNameProperty="path")
     *
     * @var File $file
     */
    protected $file;
    
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
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Project")
    */
    protected $project;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course")
    */
    protected $course; 
    
     /**
     * @ORM\OneToMany(targetEntity="Marca\ResponseBundle\Entity\Response", mappedBy="file", cascade="remove")
     */
    protected $responses;  
  
     /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="reviewed")
     */
    private $reviews;

    /**
     * @ORM\ManyToOne(targetEntity="File", inversedBy="reviews")
     * @ORM\JoinColumn(name="reviewed_id", referencedColumnName="id")
     */
    private $reviewed;
    
    /**
     * @var string $name
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;    

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;
     
     /**
     * @var integer $access
     *
     * @ORM\Column(name="access", type="integer", nullable=true)
     */
    private $access = 0;   
    
    /**
     * @ORM\OneToOne(targetEntity="Marca\DocBundle\Entity\Doc", mappedBy="file", cascade="remove")
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
     * Set file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
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
        $this->tag = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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
     * Remove portitem
     *
     * @param Marca\PortfolioBundle\Entity\Portitem $portitem
     */
    public function removePortitem(\Marca\PortfolioBundle\Entity\Portitem $portitem)
    {
        $this->portitem->removeElement($portitem);
    }

    /**
     * Add responses
     *
     * @param \Marca\ResponseBundle\Entity\Response $responses
     * @return File
     */
    public function addResponse(\Marca\ResponseBundle\Entity\Response $responses)
    {
        $this->responses[] = $responses;
    
        return $this;
    }

    /**
     * Remove responses
     *
     * @param \Marca\ResponseBundle\Entity\Response $responses
     */
    public function removeResponse(\Marca\ResponseBundle\Entity\Response $responses)
    {
        $this->responses->removeElement($responses);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return File
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * Add reviews
     *
     * @param \Marca\FileBundle\Entity\File $reviews
     * @return File
     */
    public function addReview(\Marca\FileBundle\Entity\File $reviews)
    {
        $this->reviews[] = $reviews;
    
        return $this;
    }

    /**
     * Remove reviews
     *
     * @param \Marca\FileBundle\Entity\File $reviews
     */
    public function removeReview(\Marca\FileBundle\Entity\File $reviews)
    {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set reviewed
     *
     * @param \Marca\FileBundle\Entity\File $reviewed
     * @return File
     */
    public function setReviewed(\Marca\FileBundle\Entity\File $reviewed = null)
    {
        $this->reviewed = $reviewed;
    
        return $this;
    }

    /**
     * Get reviewed
     *
     * @return \Marca\FileBundle\Entity\File 
     */
    public function getReviewed()
    {
        return $this->reviewed;
    }
}