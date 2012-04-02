<?php

namespace Marca\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Marca\TagBundle\Entity\Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\TagBundle\Entity\TagRepository")
 */
class Tag
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
     * @var string $color
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var integer $userid
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;

    /**
     * @var string $icon
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;
    
    /**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;

    /**
     * @ORM\ManyToOne(targetEntity="Tagset", inversedBy="tag")
     * @ORM\JoinColumn(name="tagset_id", referencedColumnName="id")
     */
    protected $tagset;
    
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
     * Set color
     *
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
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
     * Set icon
     *
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set tagset
     *
     * @param Marca\TagBundle\Entity\Tagset $tagset
     */
    public function setTagset(\Marca\TagBundle\Entity\Tagset $tagset)
    {
        $this->tagset = $tagset;
    }

    /**
     * Get tagset
     *
     * @return Marca\TagBundle\Entity\Tagset 
     */
    public function getTagset()
    {
        return $this->tagset;
    }
}