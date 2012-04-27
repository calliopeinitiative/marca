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
     * @var integer $userid
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;

    /**
     * @var integer $courseid
     *
     * @ORM\Column(name="courseid", type="integer")
     */
    private $courseid;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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