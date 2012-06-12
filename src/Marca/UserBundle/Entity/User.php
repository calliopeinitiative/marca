<?php
// src/Marca/UserBundle/Entity/User.php

namespace Marca\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="marca_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

     /**
     * @ORM\OneToOne(targetEntity="Profile", mappedBy="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     **/
    private $profile; 
    
    
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
     * Set profile
     *
     * @param Marca\UserBundle\Entity\Profile $profile
     */
    public function setProfile(\Marca\UserBundle\Entity\Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get profile
     *
     * @return Marca\UserBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}