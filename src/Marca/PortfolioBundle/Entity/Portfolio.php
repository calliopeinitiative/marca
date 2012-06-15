<?php

namespace Marca\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\PortfolioBundle\Entity\Portfolio
 *
 * @ORM\Table()
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
     * @var integer $fileid
     *
     * @ORM\Column(name="fileid", type="integer")
     */
    private $fileid;

    /**
     * @var integer $portOrder
     *
     * @ORM\Column(name="portOrder", type="integer")
     */
    private $portOrder;

    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="portfolio")
    */
    protected $user;    

    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\CourseBundle\Entity\Course", inversedBy="portfolio")
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
     * Set fileid
     *
     * @param integer $fileid
     */
    public function setFileid($fileid)
    {
        $this->fileid = $fileid;
    }

    /**
     * Get fileid
     *
     * @return integer 
     */
    public function getFileid()
    {
        return $this->fileid;
    }

    /**
     * Set portOrder
     *
     * @param integer $portOrder
     */
    public function setPortOrder($portOrder)
    {
        $this->portOrder = $portOrder;
    }

    /**
     * Get portOrder
     *
     * @return integer 
     */
    public function getPortOrder()
    {
        return $this->portOrder;
    }
}