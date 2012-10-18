<?php

namespace Marca\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\DocBundle\Entity\Markupset;
use Marca\DocBundle\Entity\Markup;

/*
 * Loads some users into the database
 * NOTE: Does NOT promote instructors to FOS User Status "INSTR", do this from the command line
 */

/**
 * Description of LoadMarkupsetData
 *
 * @author Ron
 */
class LoadMarkupsetData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $shared = 1;
        $name = 'Standard';
        $markupset = new Markupset();
        $user = $manager->merge($this->getReference('instr1-user')); 
        $markupset->setUser($user);
        $markupset->setShared($shared);
        $markupset->setName($name); 
        
        $name = 'Subject';
        $color = 'mc1';
        $value = 'subject';
        $markup1 = new Markup();
        $markup1->setUser($user);
        $markup1->setName($name);
        $markup1->setColor($color);
        $markup1->setValue($value);
        $markup1->addMarkupset($markupset);
        
        $name = 'Verb';
        $color = 'mc2';
        $value = 'verb';
        $markup2 = new Markup();
        $markup2->setUser($user);
        $markup2->setName($name);
        $markup2->setColor($color);
        $markup2->setValue($value);
        $markup2->addMarkupset($markupset);        
        
        $name = 'Preposition';
        $color = 'mc3';
        $value = 'preposition';
        $markup3 = new Markup();
        $markup3->setUser($user);
        $markup3->setName($name);
        $markup3->setColor($color);
        $markup3->setValue($value);
        $markup3->addMarkupset($markupset);
        
        $manager->persist($markupset);       
        $manager->persist($markup1);
        $manager->persist($markup2);
        $manager->persist($markup3);

        $markupset2 = new Markupset();
        $markupset2->setUser($user);
        $markupset2->setShared($shared);
        $markupset2->setName('Rhetoric');
        
        $markup4 = new Markup();
        $markup4->setUser($user);
        $markup4->setName('Ethos');
        $markup4->setColor('mc4');
        $markup4->setValue('Ethos');
        $markup4->setUrl('http://writingcommons.org/information-literacy/understanding-arguments/rhetorical-analysis/rhetorical-appeals/585-ethos');
        $markup4->addMarkupset($markupset2);
        
        $markup5 = new Markup();
        $markup5->setUser($user);
        $markup5->setName('Pathos');
        $markup5->setColor('mc5');
        $markup5->setValue('Pathos');
        $markup5->setUrl('http://writingcommons.org/information-literacy/understanding-arguments/rhetorical-analysis/rhetorical-appeals/591-pathos');
        $markup5->addMarkupset($markupset2);
        
        $markup6 = new Markup();
        $markup6->setUser($user);
        $markup6->setName('Logos');
        $markup6->setColor('mc6');
        $markup6->setValue('Logos');
        $markup6->setUrl('http://writingcommons.org/information-literacy/understanding-arguments/rhetorical-analysis/rhetorical-appeals/593-logos');
        $markup6->addMarkupset($markupset2);
        
        $manager->persist($markupset2);       
        $manager->persist($markup4);
        $manager->persist($markup5);
        $manager->persist($markup6);
        
        $manager->flush();
        
           
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
}
