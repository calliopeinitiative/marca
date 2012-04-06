<?php

namespace Marca\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca\DocBundle\Entity\Markup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\DocBundle\Entity\MarkupRepository")
 */
class Markup
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $function
     *
     * @ORM\Column(name="function", type="string", length=255)
     */
    private $function;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;
    
    /**
     * @var string $color
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;    

    /**
     * @var array $markupset
     *
     * @ORM\Column(name="markupset", type="array")
     */
    private $markupset;


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
     * Set function
     *
     * @param string $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * Get function
     *
     * @return string 
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * Set markupset
     *
     * @param array $markupset
     */
    public function setMarkupset($markupset)
    {
        $this->markupset = $markupset;
    }

    /**
     * Get markupset
     *
     * @return array 
     */
    public function getMarkupset()
    {
        return $this->markupset;
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
}