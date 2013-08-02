<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ReviewResponse
 *
 * @ORM\Table(name="reviewresponse")
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
     * @var string
     *
     * @ORM\Column(name="responseParagraphText", type="text", nullable=true)
     */
    private $responseParagraphText;
    
      /**
     * @var string
     *
     * @ORM\Column(name="responseShortText", type="string", nullable=true)
     */
    private $responseShortText;

    /**
     * @var boolean
     *
     * @ORM\Column(name="responseBool", type="boolean", nullable=true)
     */
    private $responseBool;

    /**
     * @var integer
     *
     * @ORM\Column(name="responseInt", type="integer", nullable=true)
     */
    private $responseInt;
    
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
     * @var boolean
     * 
     * @ORM\Column(name="helpful", type="boolean", nullable=true)
     */
    protected $helpful;

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
     * @ORM\ManyToOne(targetEntity="Review", inversedBy="reviewresponses")
     */
    private $review; 
    
    
    
    
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

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ReviewResponse
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
     * @return ReviewResponse
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
     * Set review
     *
     * @param \Marca\AssignmentBundle\Entity\Review $review
     * @return ReviewResponse
     */
    public function setReview(\Marca\AssignmentBundle\Entity\Review $review = null)
    {
        $this->review = $review;
    
        return $this;
    }

    /**
     * Get review
     *
     * @return \Marca\AssignmentBundle\Entity\Review 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set helpful
     *
     * @param boolean $helpful
     * @return ReviewResponse
     */
    public function setHelpful($helpful)
    {
        $this->helpful = $helpful;
    
        return $this;
    }

    /**
     * Get helpful
     *
     * @return boolean 
     */
    public function getHelpful()
    {
        return $this->helpful;
    }

    /**
     * Set responseParagraphText
     *
     * @param string $responseParagraphText
     * @return ReviewResponse
     */
    public function setResponseParagraphText($responseParagraphText)
    {
        $this->responseParagraphText = $responseParagraphText;
    
        return $this;
    }

    /**
     * Get responseParagraphText
     *
     * @return string 
     */
    public function getResponseParagraphText()
    {
        return $this->responseParagraphText;
    }

    /**
     * Set responseShortText
     *
     * @param string $responseShortText
     * @return ReviewResponse
     */
    public function setResponseShortText($responseShortText)
    {
        $this->responseShortText = $responseShortText;
    
        return $this;
    }

    /**
     * Get responseShortText
     *
     * @return string 
     */
    public function getResponseShortText()
    {
        return $this->responseShortText;
    }
}