<?php

namespace Marca\CourseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\CourseBundle\Entity\Course;
use Marca\CourseBundle\Entity\Roll;
use Marca\CourseBundle\Entity\Project;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enrollFixture
 *
 * @author afamiglietti
 */
class enrollFixture extends AbstractFixture implements OrderedFixtureInterface
{
     public function load(ObjectManager $manager)
    {
         $course = $manager->merge($this->getReference('course1.1'));
         $course->setPendingFlag(true);
         
         for ($i = 1; $i <= 25; $i++){
            $num = strval($i);
            $user = $manager->merge($this->getReference('stdnt'.$num.'-user'));
            $roll = new Roll();
            $roll->setRole(Roll::ROLE_PENDING);
            $roll->setUser($user);
            $roll->setStatus(1);
            $roll->setCourse($course);
        
            $manager->persist($roll);
            $manager->flush();
        }

        $course = $manager->merge($this->getReference('course2.1'));
        $course->setPendingFlag(true);   
        for ($i = 26; $i <= 50; $i++){
            $num = strval($i);
            $user = $manager->merge($this->getReference('stdnt'.$num.'-user'));
            $roll = new Roll();
            $roll->setRole(Roll::ROLE_PENDING);
            $roll->setUser($user);
            $roll->setStatus(1);
            $roll->setCourse($course);
        
            $manager->persist($roll);
            $manager->flush();
        }
         
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}

?>
