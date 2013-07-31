<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReviewResponse
 *
 * @ORM\Table('reviewresponse')
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\ReviewResponseRepository")
 */
class ReviewResponse
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
     * @var \DateTime
     *
     * @ORM\Column(name="timeCreated", type="datetime")
     */
    private $timeCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeModified", type="datetime")
     */
    private $timeModified;

    /**
     * @var string
     *
     * @ORM\Column(name="responseText", type="text")
     */
    private $responseText;

    /**
     * @var boolean
     *
     * @ORM\Column(name="responseBool", type="boolean")
     */
    private $responseBool;

    /**
     * @var integer
     *
     * @ORM\Column(name="responseInt", type="integer")
     */
    private $responseInt;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\DocBundle\Entity\Doc", cascade={"persist"})
     * @ORM\JoinColumn(name="doc_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $reviewDoc;
            
    /**
     * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $reviewer;   
    
    /**
     * @ORM\ManyToOne(targetEntity="PromptItem", inversedBy="responses", cascade={"persist"})
     * @ORM\JoinColumn(name="promptitem_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $reviewPrompt; 
    
    
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
     * Set timeCreated
     *
     * @param \DateTime $timeCreated
     * @return ReviewResponse
     */
    public function setTimeCreated($timeCreated)
    {
        $this->timeCreated = $timeCreated;
    
        return $this;
    }

    /**
     * Get timeCreated
     *
     * @return \DateTime 
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    /**
     * Set timeModified
     *
     * @param \DateTime $timeModified
     * @return ReviewResponse
     */
    public function setTimeModified($timeModified)
    {
        $this->timeModified = $timeModified;
    
        return $this;
    }

    /**
     * Get timeModified
     *
     * @return \DateTime 
     */
    public function getTimeModified()
    {
        return $this->timeModified;
    }

    /**
     * Set responseText
     *
     * @param string $responseText
     * @return ReviewResponse
     */
    public function setResponseText($responseText)
    {
        $this->responseText = $responseText;
    
        return $this;
    }

    /**
     * Get responseText
     *
     * @return string 
     */
    public function getResponseText()
    {
        return $this->responseText;
    }

    /**
     * Set responseBool
     *
     * @param boolean $responseBool
     * @return ReviewResponse
     */
    public function setResponseBool($responseBool)
    {
        $this->responseBool = $responseBool;
    
        return $this;
    }

    /**
     * Get responseBool
     *
     * @return boolean 
     */
    public function getResponseBool()
    {
        return $this->responseBool;
    }

    /**
     * Set responseInt
     *
     * @param integer $responseInt
     * @return ReviewResponse
     */
    public function setResponseInt($responseInt)
    {
        $this->responseInt = $responseInt;
    
        return $this;
    }

    /**
     * Get responseInt
     *
     * @return integer 
     */
    public function getResponseInt()
    {
        return $this->responseInt;
    }

    /**
     * Set reviewDoc
     *
     * @param \Marca\DocBundle\Entity\Doc $reviewDoc
     * @return ReviewResponse
     */
    public function setReviewDoc(\Marca\DocBundle\Entity\Doc $reviewDoc = null)
    {
        $this->reviewDoc = $reviewDoc;
    
        return $this;
    }

    /**
     * Get reviewDoc
     *
     * @return \Marca\DocBundle\Entity\Doc 
     */
    public function getReviewDoc()
    {
        return $this->reviewDoc;
    }

    /**
     * Set reviewer
     *
     * @param \Marca\UserBundle\Entity\User $reviewer
     * @return ReviewResponse
     */
    public function setReviewer(\Marca\UserBundle\Entity\User $reviewer = null)
    {
        $this->reviewer = $reviewer;
    
        return $this;
    }

    /**
     * Get reviewer
     *
     * @return \Marca\UserBundle\Entity\User 
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * Set reviewPrompt
     *
     * @param \Marca\AssignmentBundle\Entity\PromptItem $reviewPrompt
     * @return ReviewResponse
     */
    public function setReviewPrompt(\Marca\AssignmentBundle\Entity\PromptItem $reviewPrompt = null)
    {
        $this->reviewPrompt = $reviewPrompt;
    
        return $this;
    }

    /**
     * Get reviewPrompt
     *
     * @return \Marca\AssignmentBundle\Entity\PromptItem 
     */
    public function getReviewPrompt()
    {
        return $this->reviewPrompt;
    }
}