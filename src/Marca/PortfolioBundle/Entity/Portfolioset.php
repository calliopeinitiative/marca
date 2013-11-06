<?php

namespace Marca\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Portfolioset
 *
 * @ORM\Table(name="portfolioset")
 * @ORM\Entity(repositoryClass="Marca\PortfolioBundle\Entity\PortfoliosetRepository")
 */
class Portfolioset
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = 'My Portfolio';
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $user;    
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course")
    */
    protected $course;
    
    /**
     * @var boolean $port_default
     *
     * @ORM\Column(name="port_default", type="boolean")
     */
    private $port_default = true;    
    
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\PortfolioBundle\Entity\Portfolio", mappedBy="portfolioset")
    * @ORM\OrderBy({"portorder" = "ASC"})
    */
    protected $portfolioitems;
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\AssessmentBundle\Entity\Ratingset", mappedBy="portfolioset")
    */
    protected $ratingsets;     


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
     * @return Portfolioset
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
        $this->portfolioitems = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set user
     *
     * @param \Marca\UserBundle\Entity\User $user
     * @return Portfolioset
     */
    public function setUser(\Marca\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
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
     * @return Portfolioset
     */
    public function setCourse(\Marca\CourseBundle\Entity\Course $course = null)
    {
        $this->course = $course;
    
        return $this;
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
     * Add portfolioitems
     *
     * @param \Marca\PortfolioBundle\Entity\Portfolioset $portfolioitems
     * @return Portfolioset
     */
    public function addPortfolioitem(\Marca\PortfolioBundle\Entity\Portfolioset $portfolioitems)
    {
        $this->portfolioitems[] = $portfolioitems;
    
        return $this;
    }

    /**
     * Remove portfolioitems
     *
     * @param \Marca\PortfolioBundle\Entity\Portfolioset $portfolioitems
     */
    public function removePortfolioitem(\Marca\PortfolioBundle\Entity\Portfolioset $portfolioitems)
    {
        $this->portfolioitems->removeElement($portfolioitems);
    }

    /**
     * Get portfolioitems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPortfolioitems()
    {
        return $this->portfolioitems;
    }

    /**
     * Add ratingsets
     *
     * @param \Marca\AssessmentBundle\Entity\Ratingset $ratingsets
     * @return Portfolioset
     */
    public function addRatingset(\Marca\AssessmentBundle\Entity\Ratingset $ratingsets)
    {
        $this->ratingsets[] = $ratingsets;
    
        return $this;
    }

    /**
     * Remove ratingsets
     *
     * @param \Marca\AssessmentBundle\Entity\Ratingset $ratingsets
     */
    public function removeRatingset(\Marca\AssessmentBundle\Entity\Ratingset $ratingsets)
    {
        $this->ratingsets->removeElement($ratingsets);
    }

    /**
     * Get ratingsets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatingsets()
    {
        return $this->ratingsets;
    }



    /**
     * Set port_default
     *
     * @param boolean $portDefault
     * @return Portfolioset
     */
    public function setPortDefault($portDefault)
    {
        $this->port_default = $portDefault;
    
        return $this;
    }

    /**
     * Get port_default
     *
     * @return boolean 
     */
    public function getPortDefault()
    {
        return $this->port_default;
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