<?php

namespace Marca\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\PortfolioBundle\Entity\Portitem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\PortfolioBundle\Entity\PortitemRepository")
 */
class Portitem
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
    private $name = 'New portfolio item';

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description = 'Description of the item';

    /**
     * @var integer $sortorder
     *
     * @ORM\Column(name="sortorder", type="integer", nullable=true)
     */
    private $sortorder = 1;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\PortfolioBundle\Entity\Portset", inversedBy="portitem")
    */
    protected $portset;  
    
   /**
    * @ORM\ManyToMany(targetEntity="Marca\FileBundle\Entity\File", mappedBy="portitem")
    */
    protected $file;      


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

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
    }

    /**
     * Get sortorder
     *
     * @return integer 
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set portset
     *
     * @param Marca\PortfolioBundle\Entity\Portset $portset
     */
    public function setPortset(\Marca\PortfolioBundle\Entity\Portset $portset)
    {
        $this->portset = $portset;
    }

    /**
     * Get portset
     *
     * @return Marca\PortfolioBundle\Entity\Portset 
     */
    public function getPortset()
    {
        return $this->portset;
    }
    public function __construct()
    {
        $this->file = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add file
     *
     * @param Marca\FileBundle\Entity\File $file
     */
    public function addFile(\Marca\FileBundle\Entity\File $file)
    {
        $this->file[] = $file;
    }

    /**
     * Get file
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Remove file
     *
     * @param \Marca\FileBundle\Entity\File $file
     */
    public function removeFile(\Marca\FileBundle\Entity\File $file)
    {
        $this->file->removeElement($file);
    }
}