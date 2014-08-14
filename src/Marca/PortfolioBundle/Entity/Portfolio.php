<?php

namespace Marca\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\PortfolioBundle\Entity\Portfolio
 *
 * @ORM\Table(name="portfolio")
 * @ORM\Entity(repositoryClass="Marca\PortfolioBundle\Entity\PortfolioRepository")
 */
class Portfolio
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
     * @var integer $portorder
     *
     * @ORM\Column(name="portorder", type="integer")
     */
    private $portorder = 1;

    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $user;    
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course")
    */
    protected $course;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\FileBundle\Entity\File", inversedBy="portfolio")
    */
    protected $file;   
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\PortfolioBundle\Entity\Portitem")
    */
    protected $portitem; 
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\PortfolioBundle\Entity\Portfolioset", inversedBy="portfolioitems")
    */
    protected $portfolioset;     
    

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
     * Set portorder
     *
     * @param integer $portOrder
     */
    public function setPortorder($portorder)
    {
        $this->portorder = $portorder;
    }

    /**
     * Get portorder
     *
     * @return integer 
     */
    public function getPortorder()
    {
        return $this->portorder;
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
     * Set file
     *
     * @param \Marca\FileBundle\Entity\File $file
     */
    public function setFile(\Marca\FileBundle\Entity\File $file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return \Marca\FileBundle\Entity\File 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set portitem
     *
     * @param \Marca\PortfolioBundle\Entity\Portitem $portitem
     */
    public function setPortitem(\Marca\PortfolioBundle\Entity\Portitem $portitem)
    {
        $this->portitem = $portitem;
    }

    /**
     * Get portitem
     *
     * @return \Marca\PortfolioBundle\Entity\Portitem 
     */
    public function getPortitem()
    {
        return $this->portitem;
    }

    /**
     * Set portfolioset
     *
     * @param \Marca\PortfolioBundle\Entity\Portfolioset $portfolioset
     * @return Portfolio
     */
    public function setPortfolioset(\Marca\PortfolioBundle\Entity\Portfolioset $portfolioset = null)
    {
        $this->portfolioset = $portfolioset;
    
        return $this;
    }

    /**
     * Get portfolioset
     *
     * @return \Marca\PortfolioBundle\Entity\Portfolioset 
     */
    public function getPortfolioset()
    {
        return $this->portfolioset;
    }
}