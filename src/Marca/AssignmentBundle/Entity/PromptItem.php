<?php

namespace Marca\AssignmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromptItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\AssignmentBundle\Entity\PromptItemRepository")
 */
class PromptItem
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
     * @ORM\Column(name="prompt", type="text")
     */
    private $prompt;

    /**
     * @var string
     *
     * @ORM\Column(name="helpText", type="text")
     */
    private $helpText;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;


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
}
