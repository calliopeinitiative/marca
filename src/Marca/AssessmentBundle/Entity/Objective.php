<?php

namespace Marca\AssessmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Objective
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\AssessmentBundle\Entity\ObjectiveRepository")
 */
class Objective
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
     * @ORM\Column(name="objective", type="text")
     */
    private $objective;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Assessmentset", inversedBy="objective")
    */
    protected $assessmentset;      


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
     * @param string $objective
     * @return Objective
     */
    public function setObjective($objective)
    {
        $this->objective = $objective;
    
        return $this;
    }

    /**
     * Get objective
     *
     * @return string 
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Objective
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set assessmentset
     *
     * @param \Marca\AssessmentBundle\Entity\Assessmentset $assessmentset
     * @return Objective
     */
    public function setAssessmentset(\Marca\AssessmentBundle\Entity\Assessmentset $assessmentset = null)
    {
        $this->assessmentset = $assessmentset;
    
        return $this;
    }

    /**
     * Get assessmentset
     *
     * @return \Marca\AssessmentBundle\Entity\Assessmentset 
     */
    public function getAssessmentset()
    {
        return $this->assessmentset;
    }
}