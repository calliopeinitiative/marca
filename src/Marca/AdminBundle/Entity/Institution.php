<?php

namespace Marca\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Institution
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Institution
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
     * @var integer
     *
     * @ORM\Column(name="payment_type", type="integer")
     */
    private $payment_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="semester_price", type="integer")
     */
    private $semester_price;


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
     * @return Institution
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
     * Set payment_type
     *
     * @param integer $paymentType
     * @return Institution
     */
    public function setPaymentType($paymentType)
    {
        $this->payment_type = $paymentType;
    
        return $this;
    }

    /**
     * Get payment_type
     *
     * @return integer 
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * Set semester_price
     *
     * @param integer $semesterPrice
     * @return Institution
     */
    public function setSemesterPrice($semesterPrice)
    {
        $this->semester_price = $semesterPrice;
    
        return $this;
    }

    /**
     * Get semester_price
     *
     * @return integer 
     */
    public function getSemesterPrice()
    {
        return $this->semester_price;
    }
}
