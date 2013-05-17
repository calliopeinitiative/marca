<?php

namespace Marca\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\PortfolioBundle\Entity\Portset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\PortfolioBundle\Entity\PortsetRepository")
 */
class Portset
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
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var integer $shared
     *
     * @ORM\Column(name="shared", type="integer", nullable=true)
     */
    private $shared=0;      

    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $user;
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\CourseBundle\Entity\Course", mappedBy="portset")
    */
    protected $course; 
    
    
   /**
    * @ORM\OneToMany(targetEntity="Marca\PortfolioBundle\Entity\Portitem", mappedBy="portset")
    * @ORM\OrderBy({"sortorder" = "ASC"})
    */
    protected $portitem;     

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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
    public function __construct()
    {
        $this->portitem = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add portitem
     *
     * @param Marca\PortfolioBundle\Entity\Portset $portitem
     */
    public function addPortset(\Marca\PortfolioBundle\Entity\Portset $portitem)
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

    /**
     * Add course
     *
     * @param Marca\CourseBundle\Entity\Course $course
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
     * Add portitem
     *
     * @param Marca\PortfolioBundle\Entity\Portitem $portitem
     */
    public function addPortitem(\Marca\PortfolioBundle\Entity\Portitem $portitem)
    {
        $this->portitem[] = $portitem;
    }

    /**
     * Remove course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     */
    public function removeCourse(\Marca\CourseBundle\Entity\Course $course)
    {
        $this->course->removeElement($course);
    }

    /**
     * Remove portitem
     *
     * @param \Marca\PortfolioBundle\Entity\Portitem $portitem
     */
    public function removePortitem(\Marca\PortfolioBundle\Entity\Portitem $portitem)
    {
        $this->portitem->removeElement($portitem);
    }

    /**
     * Set shared
     *
     * @param integer $shared
     * @return Portset
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
}