<?php

namespace Marca\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; 
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Marca\ForumBundle\Entity\Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\ForumBundle\Entity\CommentRepository")
 */
class Comment
{
    /**
     * @var integer $id
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
    * @ORM\ManyToOne(targetEntity="Marca\ForumBundle\Entity\Forum", inversedBy="comments")
    */
    protected $forum;  
    
    /**
    * @ORM\OneToMany(targetEntity="Marca\ForumBundle\Entity\Reply", mappedBy="comment")
    */
    protected $replies;     
    
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

    /**
     * Set forum
     *
     * @param Marca\ForumBundle\Entity\Forum $forum
     */
    public function setForum(\Marca\ForumBundle\Entity\Forum $forum)
    {
        $this->forum = $forum;
    }

    /**
     * Get forum
     *
     * @return Marca\ForumBundle\Entity\Forum 
     */
    public function getForum()
    {
        return $this->forum;
    }

    public function __construct()
    {
        $this->replies = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add replies
     *
     * @param Marca\ForumBundle\Entity\Reply $replies
     */
    public function addReply(\Marca\ForumBundle\Entity\Reply $replies)
    {
        $this->replies[] = $replies;
    }

    /**
     * Get replies
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getReplies()
    {
        return $this->replies;
    }
}