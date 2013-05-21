<?php

namespace Marca\AssessmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Rating
 *
 * @ORM\Table(name="rating")
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
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Objective")
    */
    protected $objective;
    
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Scaleitem")
    */
    protected $scaleitem;
    
         
    /**
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Ratingset", inversedBy="ratings")
    */
    protected $ratingset; 
      


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