<?php

namespace Marca\GradebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gradeset
 *
 * @ORM\Table(name="gradeset")
 * @ORM\Entity(repositoryClass="Marca\GradebookBundle\Entity\GradesetRepository")
 */
class Gradeset
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
     * @ORM\OneToMany(targetEntity="Marca\GradebookBundle\Entity\Category", mappedBy="gradeset")
     */
    protected $categories;

    /**
     * @ORM\OneToOne(targetEntity="Marca\CourseBundle\Entity\Course", mappedBy="gradeset")
     */
    protected $course;

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
     * @return Gradeset
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
     * Set course
     *
     * @param \Marca\CourseBundle\Entity\Course $course
     * @return Gradeset
     */
    public function setCourse(\Marca\CourseBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \Marca\CourseBundle\Entity\Course 
     */
    public function getCourse()
    {
        return $this->course;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categories
     *
     * @param \Marca\GradebookBundle\Entity\Category $categories
     * @return Gradeset
     */
    public function addCategory(\Marca\GradebookBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Marca\GradebookBundle\Entity\Category $categories
     */
    public function removeCategory(\Marca\GradebookBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
