<?php

namespace Marca\GradebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grade
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Marca\GradebookBundle\Entity\GradeRepository")
 */
class Grade
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
     * @ORM\Column(name="grade", type="decimal")
     */
    private $grade;


    /**
     * @ORM\ManyToOne(targetEntity="Marca\GradebookBundle\Entity\Category")
     */
    protected $category;


    /**
     * @ORM\OneToOne(targetEntity="Marca\FileBundle\Entity\File")
     */
    protected $file;


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
     * Set grade
     *
     * @param string $grade
     * @return Grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set file
     *
     * @param \Marca\FileBundle\Entity\File $file
     * @return Grade
     */
    public function setFile(\Marca\FileBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Marca\FileBundle\Entity\File 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set category
     *
     * @param \Marca\GradebookBundle\Entity\Category $category
     * @return Grade
     */
    public function setCategory(\Marca\GradebookBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Marca\GradebookBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
