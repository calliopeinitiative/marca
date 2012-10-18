<?php

namespace Marca\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\TagBundle\Entity\Tagset;
use Marca\TagBundle\Entity\Tag;

/*
 * Loads some users into the database
 * NOTE: Does NOT promote instructors to FOS User Status "INSTR", do this from the command line
 */

/**
 * Description of LoadTagsetData
 *
 * @author Ron
 */
class LoadTagsetData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $shared = 1;
        $name = 'Standard';
        $tagset = new Tagset();
        $user = $manager->merge($this->getReference('instr1-user')); 
        $course = $manager->merge($this->getReference('course1.1')); 
        $tagset->setUser($user);
        $tagset->setShared($shared);
        $tagset->setName($name); 
        $tagset->addCourse($course);
        
        $name = 'Draft1';
        $color = 'tc1';
        $tag1 = new Tag();
        $tag1->setUser($user);
        $tag1->setName($name);
        $tag1->setColor($color);
        $tag1->setShared($shared);
        $tag1->addTagset($tagset);
        
        $name = 'Draft2';
        $color = 'tc2';
        $tag2 = new Tag();
        $tag2->setUser($user);
        $tag2->setName($name);
        $tag2->setColor($color);
        $tag2->setShared($shared);
        $tag2->addTagset($tagset);        
        
        $name = 'Draft3';
        $color = 'tc3';
        $tag3 = new Tag();
        $tag3->setUser($user);
        $tag3->setName($name);
        $tag3->setColor($color);
        $tag3->setShared($shared);
        $tag3->addTagset($tagset);
        
        $manager->persist($tagset);       
        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->persist($tag3);
        $manager->flush();
        
           
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }
}
