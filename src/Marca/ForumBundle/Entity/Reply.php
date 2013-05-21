<?php

namespace Marca\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Marca\ForumBundle\Entity\Reply
 *
 * @ORM\Table(name="reply")
 * @ORM\Entity(repositoryClass="Marca\ForumBundle\Entity\ReplyRepository")
 */
class Reply
{
    /**
     * @var integer $id
     * 
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var text $body
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marca\ForumBundle\Entity\Comment", inversedBy="replies")
    */
    protected $comment;    

    /**
    * @ORM\ManyToOne(targetEntity="Marca\UserBundle\Entity\User")
    */
    protected $user;    
   
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
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }



    /**
     * Set comment
     *
     * @param Marca\ForumBundle\Entity\Comment $comment
     */
    public function setComment(\Marca\ForumBundle\Entity\Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return Marca\ForumBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param Marca\UserBundle\Entity\User $user
     */
    public function setUser(\Marca\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Marca\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}