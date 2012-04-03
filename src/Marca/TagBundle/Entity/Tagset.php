<?php

namespace Marca\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Marca\TagBundle\Entity\Tagset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\TagBundle\Entity\TagsetRepository")
 */
class Tagset
{
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
     * @var integer $userid
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;
    
    
   /**
    * @ORM\ManyToMany(targetEntity="Marca\TagBundle\Entity\Tag", mappedBy="tagset")
    */
    protected $tag; 
    
   /**
     * @ORM\ManyToMany(targetEntity="Marca\CourseBundle\Entity\Course")
     * @ORM\JoinTable(name="course_tagset",
     *      joinColumns={@ORM\JoinColumn(name="Tagset_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="Course_id", referencedColumnName="id")})
    */
    protected $course;     
    
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
     * Add course
     *
     * @param Marca\TagBundle\Entity\Course $course
     */
    public function addCourse(\Marca\TagBundle\Entity\Course $course)
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
}