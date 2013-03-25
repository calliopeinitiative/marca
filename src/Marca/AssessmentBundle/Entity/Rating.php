<?php

namespace Marca\AssessmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Rating
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\AssessmentBundle\Entity\RatingRepository")
 */
class Rating
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
     * @var integer
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Scaleitem", inversedBy="rating")
    */
    protected $scaleitem;     
    
    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @Gedmo\Timestampable(on="create")
    */
    protected $created;
    
    
    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @Gedmo\Timestampable(on="update")
    */
    protected $updated;    


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
     * Set value
     *
     * @param integer $value
     * @return Rating
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Rating
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Rating
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set scaleitem
     *
     * @param \Marca\AssessmentBundle\Entity\Scaleitem $scaleitem
     * @return Rating
     */
    public function setScaleitem(\Marca\AssessmentBundle\Entity\Scaleitem $scaleitem = null)
    {
        $this->scaleitem = $scaleitem;
    
        return $this;
    }

    /**
     * Get scaleitem
     *
     * @return \Marca\AssessmentBundle\Entity\Scaleitem 
     */
    public function getScaleitem()
    {
        return $this->scaleitem;
    }
}