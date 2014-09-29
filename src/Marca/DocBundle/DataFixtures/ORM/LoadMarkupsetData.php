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
       
        $user = $manager->merge($this->getReference('instr1-user')); 
        //$markupset = $this->createMarkupSet('Standard', 1, $user);
        
        //$markup1 = $this->createMarkup('Subject', $user, 'mc1', 'subject', $markupset);
        //$markup2 = $this->createMarkup('Verb', $user, 'mc2', 'verb', $markupset);
        //$markup3 = $this->createMarkup('Preposition', $user, 'mc3', 'preposition', $markupset);       
        
        //$manager->persist($markupset);       
        //$manager->persist($markup1);
        //$manager->persist($markup2);
        //$manager->persist($markup3);

        $markupset2 = $this->createMarkupSet('Rhetoric', 1, $user);

        $markup4 = $this->createMarkup('Ethos', $user, 'mc4', 'ethos', $markupset2, 'http://writingcommons.org/information-literacy/understanding-arguments/rhetorical-analysis/rhetorical-appeals/585-ethos');
        $markup5 = $this->createMarkup('Pathos', $user, 'mc5' , 'pathos', $markupset2, 'http://writingcommons.org/information-literacy/understanding-arguments/rhetorical-analysis/rhetorical-appeals/591-pathos');
        $markup6 = $this->createMarkup('Logos', $user, 'mc6', 'logos', $markupset2, 'http://writingcommons.org/information-literacy/understanding-arguments/rhetorical-analysis/rhetorical-appeals/593-logos');
        
       for ($i=2; $i <=25; $i++){
            $num = strval($i);
            $extrauser = $manager->merge($this->getReference('instr'.$num.'-user'));
            $markupset2->addUser($extrauser);
        }
              
        
        $manager->persist($markupset2);       
        $manager->persist($markup4);
        $manager->persist($markup5);
        $manager->persist($markup6);
        
        $markupset3 = $this->createMarkupSet('Evidence', 1, $user);
 
        $markup7 = $this->createMarkup('Context', $user, 'mc7', 'context', $markupset3, null, 'Provide more context for this quote');
        $markup8 = $this->createMarkup('Interpret', $user, 'mc8', 'interpret', $markupset3, null, 'Interpret this quote');
        $markup9 = $this->createMarkup('Connect', $user, 'mc9', 'connect', $markupset3, null, 'Connect this evidence to your argument');
        
        for ($j=2; $j <=25; $j++){
            $num = strval($j);
            $extrauser2 = $manager->merge($this->getReference('instr'.$num.'-user'));
            $markupset3->addUser($extrauser2);
        }
        
        //$extrauser = $manager->merge($this->getReference('instr2-user'));
        //$markupset3->addUser($extrauser);
        
        $manager->persist($markupset3);
        $manager->persist($markup7);
        $manager->persist($markup8);
        $manager->persist($markup9);
        
        $markupset4 = $this->createMarkupSet('Grammar', 1, $user);
        
        $markup10 = $this->createMarkup('Comma_Splice', $user, 'mc1', 'comma_splice', $markupset4, 'http://owl.english.purdue.edu/engagement/index.php?category_id=2&sub_category_id=1&article_id=34');
        $markup11 = $this->createMarkup('Passive_Voice', $user, 'mc2', 'passive_voice', $markupset4, 'http://owl.english.purdue.edu/owl/resource/539/1/');
        $markup12 = $this->createMarkup('Expletive_Construction', $user, 'mc3', 'expletive_construction', $markupset4, 'http://owl.english.purdue.edu/owl/resource/539/1/');
        $markup13 = $this->createMarkup('Subject_Verb_Agreement', $user, 'mc4', 'subject_verb_agreement', $markupset4, 'http://writingcommons.org/style/grammar/subject-verb-agreement');
        $markup14 = $this->createMarkup('Pronoun_antecedent_agreement ', $user, 'mc5', 'pronoun_antecedent_agreement', $markupset4, 'http://writingcommons.org/style/grammar/subject-pronoun-agreement');
        
        for ($i=2; $i <=25; $i++){
            $num = strval($i);
            $extrauser = $manager->merge($this->getReference('instr'.$num.'-user'));
            $markupset4->addUser($extrauser);
        }
        
        //$extrauser = $manager->merge($this->getReference('instr2-user'));
        //$markupset4->addUser($extrauser);
        
        $manager->persist($markupset4);
        $manager->persist($markup10);
        $manager->persist($markup11);
        $manager->persist($markup12);
        $manager->persist($markup13);
        $manager->persist($markup14);
        
       
        $manager->flush();
        
        $this->addReference('markupset2', $markupset2);
        $this->addReference('markupset3', $markupset3);
        $this->addReference('markupset4', $markupset4);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
    
    private function createMarkupSet($name, $shared, $user)
    {
        $markupset = new Markupset();
        $markupset->setName($name);
        $markupset->setShared($shared);
        $markupset->setOwner($user);
        
        return $markupset;
    }
    
    private function createMarkup($name, $user, $color, $value, $markupset, $url=null, $mouseover=null)
    {
        $markup = new Markup();
        $markup->setName($name);
        $markup->setColor($color);
        $markup->setUser($user);
        $markup->setValue($value);
        $markup->setUrl($url);
        $markup->setMouseover($mouseover);
        $markup->addMarkupset($markupset);
        return $markup;
    }
}
