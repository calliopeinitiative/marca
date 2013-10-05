<?php

namespace Marca\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\UserBundle\Entity\User;

/*
 * Loads some users into the database
 * NOTE: Does NOT promote instructors to FOS User Status "INSTR", do this from the command line
 */

/**
 * Description of LoadUserData
 *
 * @author afamiglietti
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 25; $i++){
            $num = strval($i);
            $userInstr = new User();
            $userInstr->setUsername('testInstructor'.$num);
            $userInstr->setPlainPassword('test'.$num);
            $userInstr->setEmail('test'.$num.'@someemail.com');
            $userInstr->setFirstname('Test'.$num);
            $userInstr->setLastname('Instructor'.$num);
            $userInstr->setEnabled(True);
            $userInstr->setInstitution(
              $this->getReference('institution')
            );

            $manager->persist($userInstr);
            $manager->flush();

            $this->addReference('instr'.$num.'-user', $userInstr);
        }
        for ($i = 1; $i <= 100; $i++){
            $num = strval($i);
            $userStudent = new User();
            $userStudent->setUsername('testStudent'.$num);
            $userStudent->setPlainPassword('test'.$num);
            $userStudent->setEmail('teststudent'.$num.'@someemail.com');
            $userStudent->setFirstname('Test'.$num);
            $userStudent->setLastname('Student'.$num);
            $userStudent->setEnabled(True);
            $userStudent->setInstitution(
              $this->getReference('institution')
            );
        
            $manager->persist($userStudent);
            $manager->flush();
        
            $this->addReference('stdnt'.$num.'-user', $userStudent);   
        }
        
    
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}


