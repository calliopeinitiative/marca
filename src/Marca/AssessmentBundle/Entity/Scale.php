<?php

namespace Marca\AssessmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scale
 *
 * @ORM\Table(name="scale")
 * @ORM\Entity(repositoryClass="Marca\AssessmentBundle\Entity\ScaleRepository")
 */
class Scale
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type = 1;
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\AssessmentBundle\Entity\Objective", mappedBy="scale")
    */
    protected $objective;


    /**
     * @ORM\OneToMany(targetEntity="Marca\AssignmentBundle\Entity\PromptItem", mappedBy="scale")
     */
    protected $promptitem;



    /**
    * @ORM\OneToMany(targetEntity="Marca\AssessmentBundle\Entity\Scaleitem", mappedBy="scale")
    */
    protected $scaleitems;    

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
     * @return Scale
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * Set type
     *
     * @param integer $type
     * @return Scale
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set objective
     *
     * @param \Marca\AssessmentBundle\Entity\Objective $objective
     * @return Scale
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
     * Constructor
     */
    public function __construct()
    {
        $this->scaleitems = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add scaleitems
     *
     * @param \Marca\AssessmentBundle\Entity\Scaleitem $scaleitems
     * @return Scale
     */
    public function addScaleitem(\Marca\AssessmentBundle\Entity\Scaleitem $scaleitems)
    {
        $this->scaleitems[] = $scaleitems;
    
        return $this;
    }

    /**
     * Remove scaleitems
     *
     * @param \Marca\AssessmentBundle\Entity\Scaleitem $scaleitems
     */
    public function removeScaleitem(\Marca\AssessmentBundle\Entity\Scaleitem $scaleitems)
    {
        $this->scaleitems->removeElement($scaleitems);
    }

    /**
     * Get scaleitems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getScaleitems()
    {
        return $this->scaleitems;
    }

    /**
     * Add objective
     *
     * @param \Marca\AssessmentBundle\Entity\Objective $objective
     * @return Scale
     */
    public function addObjective(\Marca\AssessmentBundle\Entity\Objective $objective)
    {
        $this->objective[] = $objective;
    
        return $this;
    }

    /**
     * Remove objective
     *
     * @param \Marca\AssessmentBundle\Entity\Objective $objective
     */
    public function removeObjective(\Marca\AssessmentBundle\Entity\Objective $objective)
    {
        $this->objective->removeElement($objective);
    }
}