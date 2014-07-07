<?php

namespace Marca\GradebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Marca\GradebookBundle\Entity\CategoryRepository")
 */
class Category
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
     * @var string
     *
     * @ORM\Column(name="percent", type="decimal")
     */
    private $percent;

    /**
     * @ORM\ManyToOne(targetEntity="Marca\GradebookBundle\Entity\Gradeset", inversedBy="categories")
     */
    protected $gradeset;


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
     * @return Category
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
     * Set percent
     *
     * @param string $percent
     * @return Category
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return string 
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set gradeset
     *
     * @param \Marca\GradebookBundle\Entity\Gradeset $gradeset
     * @return Category
     */
    public function setGradeset(\Marca\GradebookBundle\Entity\Gradeset $gradeset = null)
    {
        $this->gradeset = $gradeset;

        return $this;
    }

    /**
     * Get gradeset
     *
     * @return \Marca\GradebookBundle\Entity\Gradeset 
     */
    public function getGradeset()
    {
        return $this->gradeset;
    }
}
