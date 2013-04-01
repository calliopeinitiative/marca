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
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Objective")
    */
    protected $objective;
         
    /**
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Ratingset", inversedBy="ratings")
    */
    protected $ratingset; 
    
    
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
     * Set objective
     *
     * @param \Marca\AssessmentBundle\Entity\Objective $objective
     * @return Rating
     */
    public function setObjective(\Marca\AssessmentBundle\Entity\Objective $objective = null)
    {
        $this->objective = $objective;
    
        return $this;
    }

    /**
     * Get objective
     *
     * @return \Marca\AssessmentBundle\Entity\Objective 
     */
    public function getObjective()
    {
        return $this->objective;
    }
   


    /**
     * Set ratingset
     *
     * @param \Marca\AssessmentBundle\Entity\Ratingset $ratingset
     * @return Rating
     */
    public function setRatingset(\Marca\AssessmentBundle\Entity\Ratingset $ratingset = null)
    {
        $this->ratingset = $ratingset;
    
        return $this;
    }

    /**
     * Get ratingset
     *
     * @return \Marca\AssessmentBundle\Entity\Ratingset 
     */
    public function getRatingset()
    {
        return $this->ratingset;
    }
}