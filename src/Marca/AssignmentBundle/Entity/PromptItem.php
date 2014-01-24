<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromptItem
 *
 * @ORM\Table(name="promptitem")
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\PromptItemRepository")
 */
class PromptItem
{
    
    const TYPE_SHORTTEXT=0;
    const TYPE_PARAGRAPHTEXT=1;
    const TYPE_SCALE=2;
    const TYPE_BOOLEAN=3;
    const TYPE_NORESPONSE=4;
    
    
    
    
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
     * @ORM\Column(name="prompt", type="text")
     */
    private $prompt;

    /**
     * @var string
     *
     * @ORM\Column(name="helpText", type="text", nullable=true)
     */
    private $helpText;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\AssessmentBundle\Entity\Scale", cascade={"persist"})
     * @ORM\JoinColumn(name="scale_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $scale; 
     
     /**
      * @ORM\OneToMany(targetEntity="ReviewResponse", mappedBy="reviewPrompt")
      */
    private $responses;
    
    /**
     * @ORM\ManyToOne(targetEntity="ReviewRubric", inversedBy="promptitems", cascade={"persist"})
     * @ORM\JoinColumn(name="reviewrubric_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $reviewRubric;

    /**
     * @var integer $sortOrder
     *
     * @ORM\Column(name="sortOrder", type="integer")
     */
    private $sortOrder=0;
    
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
     * Set prompt
     *
     * @param string $prompt
     * @return PromptItem
     */
    public function setPrompt($prompt)
    {
        $this->prompt = $prompt;
    
        return $this;
    }

    /**
     * Get prompt
     *
     * @return string 
     */
    public function getPrompt()
    {
        return $this->prompt;
    }

    /**
     * Set helpText
     *
     * @param string $helpText
     * @return PromptItem
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;
    
        return $this;
    }

    /**
     * Get helpText
     *
     * @return string 
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return PromptItem
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
     * Set sortOrder
     *
     * @param integer $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->responses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set scale
     *
     * @param \Marca\AssessmentBundle\Entity\Scale $scale
     * @return PromptItem
     */
    public function setScale(\Marca\AssessmentBundle\Entity\Scale $scale = null)
    {
        $this->scale = $scale;
    
        return $this;
    }

    /**
     * Get scale
     *
     * @return \Marca\AssessmentBundle\Entity\Scale 
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Add response
     *
     * @param \Marca\AssignmentBundle\Entity\ReviewResponse $response
     * @return PromptItem
     */
    public function addResponse(\Marca\AssignmentBundle\Entity\ReviewResponse $response)
    {
        $this->responses[] = $response;
    
        return $this;
    }

    /**
     * Remove response
     *
     * @param \Marca\AssignmentBundle\Entity\ReviewResponse $response
     */
    public function removeResponse(\Marca\AssignmentBundle\Entity\ReviewResponse $response)
    {
        $this->responses->removeElement($response);
    }

    /**
     * Get response
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Set reviewRubric
     *
     * @param \Marca\AssignmentBundle\Entity\ReviewRubric $reviewRubric
     * @return PromptItem
     */
    public function setReviewRubric(\Marca\AssignmentBundle\Entity\ReviewRubric $reviewRubric = null)
    {
        $this->reviewRubric = $reviewRubric;
    
        return $this;
    }

    /**
     * Get reviewRubric
     *
     * @return \Marca\AssignmentBundle\Entity\ReviewRubric 
     */
    public function getReviewRubric()
    {
        return $this->reviewRubric;
    }
}