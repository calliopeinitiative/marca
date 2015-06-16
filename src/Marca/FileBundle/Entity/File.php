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
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="Marca\FileBundle\Entity\FileRepository")
 * @Vich\Uploadable
 */
class File
{

     /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"audio/mpeg", "application/vnd.ms-office", "image/gif", "image/png", "image/jpeg", "image/pjpeg", "application/pdf",
      * "application/vnd.oasis.opendocument.text", "application/vnd.oasis.opendocument.presentation",
      * "application/vnd.oasis.opendocument.spreadsheet", "application/msword", "application/mspowerpoint",
      * "application/excel", "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
      * "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      * "application/zip"}
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
     * @var string $etherpaddoc
     * @ORM\Column(name="etherpaddoc", type="string", nullable=True, length=255)
     */

    private $etherpaddoc;

    /**
     * @var string $etherpadgroup
     * @ORM\Column(name="etherpadgroup", type="string", nullable=True, length=255)
     */
    private $etherpadgroup;
 
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
     * @ORM\OneToOne(targetEntity="Marca\GradebookBundle\Entity\Grade", mappedBy="file")
     */
    protected $grade;
    

    /**
     * @ORM\OneToMany(targetEntity="Marca\AssignmentBundle\Entity\Review", mappedBy="file")
     */
    protected $feedback;


    /**
     * @ORM\OneToMany(targetEntity="Marca\DocBundle\Entity\Tracking", mappedBy="file")
     * @ORM\OrderBy({"markup" = "ASC"})
     * though this orderBy throws an invalid entity error, it works for the sort on doc display
     */
    protected $tracking;


    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="reviewed")
     */
    private $reviews;

    /**
     * @ORM\ManyToOne(targetEntity="File", inversedBy="reviews")
     * @ORM\JoinColumn(onDelete="SET NULL")
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
    * @ORM\OneToMany(targetEntity="Marca\PortfolioBundle\Entity\Portfolio", mappedBy="file")
    */
    protected $portfolio;  

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
     * @param \Marca\CourseBundle\Entity\Project $project
     */
    public function setProject(\Marca\CourseBundle\Entity\Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return \Marca\CourseBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set doc
     *
     * @param \Marca\DocBundle\Entity\Doc $doc
     */
    public function setDoc(\Marca\DocBundle\Entity\Doc $doc)
    {
        $this->doc = $doc;
    }

    /**
     * Get doc
     *
     * @return \Marca\DocBundle\Entity\Doc
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
     * @param \Marca\TagBundle\Entity\Tag $tag
     */
    public function addTag(\Marca\TagBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;
    }

    /**
     * Get tag
     *
     * @return \Doctrine\Common\Collections\Collection
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
     * Remove tag
     *
     * @param \Marca\TagBundle\Entity\Tag $tag
     */
    public function removeTag(\Marca\TagBundle\Entity\Tag $tag)
    {
        $this->tag->removeElement($tag);
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
     * Add portfolio
     *
     * @param \Marca\PortfolioBundle\Entity\Portfolio $portfolio
     * @return File
     */
    public function addPortfolio(\Marca\PortfolioBundle\Entity\Portfolio $portfolio)
    {
        $this->portfolio[] = $portfolio;
    
        return $this;
    }

    /**
     * Remove portfolio
     *
     * @param \Marca\PortfolioBundle\Entity\Portfolio $portfolio
     */
    public function removePortfolio(\Marca\PortfolioBundle\Entity\Portfolio $portfolio)
    {
        $this->portfolio->removeElement($portfolio);
    }

    /**
     * Get portfolio
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }

    /**
     * Set grade
     *
     * @param \Marca\GradebookBundle\Entity\Grade $grade
     * @return File
     */
    public function setGrade(\Marca\GradebookBundle\Entity\Grade $grade = null)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return \Marca\GradebookBundle\Entity\Grade 
     */
    public function getGrade()
    {
        return $this->grade;
    }



    /**
     * Add tracking
     *
     * @param \Marca\DocBundle\Entity\Tracking $tracking
     * @return File
     */
    public function addTracking(\Marca\DocBundle\Entity\Tracking $tracking)
    {
        $this->tracking[] = $tracking;

        return $this;
    }

    /**
     * Remove tracking
     *
     * @param \Marca\DocBundle\Entity\Tracking $tracking
     */
    public function removeTracking(\Marca\DocBundle\Entity\Tracking $tracking)
    {
        $this->tracking->removeElement($tracking);
    }

    /**
     * Get tracking
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTracking()
    {
        return $this->tracking;
    }

    /**
     * Add feedback
     *
     * @param \Marca\AssignmentBundle\Entity\Review $feedback
     * @return File
     */
    public function addFeedback(\Marca\AssignmentBundle\Entity\Review $feedback)
    {
        $this->feedback[] = $feedback;

        return $this;
    }

    /**
     * Remove feedback
     *
     * @param \Marca\AssignmentBundle\Entity\Review $feedback
     */
    public function removeFeedback(\Marca\AssignmentBundle\Entity\Review $feedback)
    {
        $this->feedback->removeElement($feedback);
    }

    /**
     * Get feedback
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Set etherpaddoc
     *
     * @param string $etherpaddoc
     * @return File
     */
    public function setEtherpaddoc($etherpaddoc)
    {
        $this->etherpaddoc = $etherpaddoc;

        return $this;
    }

    /**
     * Get etherpaddoc
     *
     * @return string 
     */
    public function getEtherpaddoc()
    {
        return $this->etherpaddoc;
    }

    /**
     * Set etherpadgroup
     *
     * @param string $etherpadgroup
     * @return File
     */
    public function setEtherpadgroup($etherpadgroup)
    {
        $this->etherpadgroup = $etherpadgroup;

        return $this;
    }

    /**
     * Get etherpadgroup
     *
     * @return string 
     */
    public function getEtherpadgroup()
    {
        return $this->etherpadgroup;
    }
}
