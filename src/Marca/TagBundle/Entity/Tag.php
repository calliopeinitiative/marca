<?php

namespace Marca\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Marca\TagBundle\Entity\Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\TagBundle\Entity\TagRepository")
 */
class Tag
{
    
    const LOCAL=0;
    const SHARED=1;
    
    
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
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", inversedBy="tag")
    */
    protected $user;   

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
     * @var integer $shared
     *
     * @ORM\Column(name="shared", type="integer", nullable=true)
     */
    private $shared=0;      

   /**
    * @ORM\ManyToMany(targetEntity="Marca\TagBundle\Entity\Tagset", inversedBy="tag")
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
    public function __construct()
    {
        $this->tagset = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set sort
     *
     * @param integer $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Add tagset
     *
     * @param Marca\TagBundle\Entity\Tagset $tagset
     */
    public function addTagset(\Marca\TagBundle\Entity\Tagset $tagset)
    {
        $this->tagset[] = $tagset;
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
     * Remove tagset
     *
     * @param Marca\TagBundle\Entity\Tagset $tagset
     */
    public function removeTagset(\Marca\TagBundle\Entity\Tagset $tagset)
    {
        $this->tagset->removeElement($tagset);
    }

    /**
     * Set shared
     *
     * @param integer $shared
     * @return Tag
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