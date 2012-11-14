<?php

namespace Marca\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Marca\DocBundle\Entity\Doc
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\DocBundle\Entity\DocRepository")
 */
class Doc
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
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;
    
   /**
    * @ORM\OneToOne(targetEntity="Marca\FileBundle\Entity\File", inversedBy="doc")
    * @ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="Cascade")
   */
    private $file;
      

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
     * @ORM\OneToOne(targetEntity="Marca\DocBundle\Entity\Doc")
     * @ORM\JoinColumn(name="autosave_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $autosaveDoc;
    
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
     * Set file
     *
     * @param Marca\FileBundle\Entity\File $file
     */
    public function setFile(\Marca\FileBundle\Entity\File $file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return Marca\FileBundle\Entity\File 
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Set autosave doc
     *
     * @param Marca\DocBundle\Entity\Doc $doc
     */
    public function setAutosaveDoc(\Marca\DocBundle\Entity\Doc $doc)
    {
        $this->autosaveDoc = $doc;
    }

    /**
     * Get autosave doc
     *
     * @return Marca\DocBundle\Entity\Doc
     */
    public function getAutosaveDoc()
    {
        return $this->autosaveDoc;
    }
    
    public function isOwner($user)
    {
        if($user == $this->file->getUser()){
            return true;
        }
        else{
            return false;
        }
    }    
    
}