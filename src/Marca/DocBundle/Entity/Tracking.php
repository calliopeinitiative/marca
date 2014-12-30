<?php

namespace Marca\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tracking
 *
 * @ORM\Table(name="tracking")
 * @ORM\Entity(repositoryClass="Marca\DocBundle\Entity\TrackingRepository")
 */
class Tracking
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Marca\DocBundle\Entity\Markup")
     */
    protected $markup;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\FileBundle\Entity\File", inversedBy="tracking")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Tracking
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set markup
     *
     * @param \Marca\DocBundle\Entity\Markup $markup
     * @return Tracking
     */
    public function setMarkup(\Marca\DocBundle\Entity\Markup $markup = null)
    {
        $this->markup = $markup;

        return $this;
    }

    /**
     * Get markup
     *
     * @return \Marca\DocBundle\Entity\Markup 
     */
    public function getMarkup()
    {
        return $this->markup;
    }



    /**
     * Set file
     *
     * @param \Marca\FileBundle\Entity\File $file
     * @return Tracking
     */
    public function setFile(\Marca\FileBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
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
}
