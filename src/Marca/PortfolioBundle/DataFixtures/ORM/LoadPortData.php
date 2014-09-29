<?php

namespace Marca\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\PortfolioBundle\Entity\Portitem;
use Marca\PortfolioBundle\Entity\Portset;



/*
 * Sets a default portfolio item 
 * 
 */

/**
 * 
 *
 * @author ssteger
 */
class LoadPortData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
       $portset = new Portset();
       $user = $manager->merge($this->getReference('instr1-user'));
       $portset->setUser($user);
       $portset->setName('FYC Standard');
       $portset->setDescription('The standard portfolio for UGA FYC');
       $portset->setShared(2); //Make default
       
       $portitem1  = new Portitem();
       $portitem1->setPortset($portset);
       $portitem1->setName('Biography');
       $portitem1->setDescription('A short bio of the author');
       $portitem1->setSortorder(1);
       
       $portitem2  = new Portitem();
       $portitem2->setPortset($portset);
       $portitem2->setName('IRE');
       $portitem2->setDescription('Introductory Reflective Essay');
       $portitem2->setSortorder(2);
       
       $portitem3  = new Portitem();
       $portitem3->setPortset($portset);
       $portitem3->setName('Original Essay 1');
       $portitem3->setDescription('Not displayed in Portfolio view');
       $portitem3->setSortorder(3);
       
       $portitem4  = new Portitem();
       $portitem4->setPortset($portset);
       $portitem4->setName('Revised Essay 1');
       $portitem4->setDescription('');
       $portitem4->setSortorder(4);
       
       $portitem5  = new Portitem();
       $portitem5->setPortset($portset);
       $portitem5->setName('Original Essay 2');
       $portitem5->setDescription('Not displayed in Portfolio view');
       $portitem5->setSortorder(5);
       
       $portitem6  = new Portitem();
       $portitem6->setPortset($portset);
       $portitem6->setName('Revised Essay 2');
       $portitem6->setDescription('');
       $portitem6->setSortorder(6);
       
       $portitem7  = new Portitem();
       $portitem7->setPortset($portset);
       $portitem7->setName('Exhibit of Revision Process');
       $portitem7->setDescription('Tracks the revision process for a section of an essay');
       $portitem7->setSortorder(7);

       $portitem8  = new Portitem();
       $portitem8->setPortset($portset);
       $portitem8->setName('Exhibit of Peer Review Process');
       $portitem8->setDescription('Exhibits your peer review of your partner\'s paper');
       $portitem8->setSortorder(8);
       
       $portitem9  = new Portitem();
       $portitem9->setPortset($portset);
       $portitem9->setName('Wild Card');
       $portitem9->setDescription('Another example of your writing');
       $portitem9->setSortorder(9);
       
       
       $portset2 = new Portset();
       $user = $manager->merge($this->getReference('instr1-user'));
       $portset2->setUser($user);
       $portset2->setName('Open Portfolio');
       $portset2->setDescription('An open template for a portfolio');
       
       $portitem10  = new Portitem();
       $portitem10->setPortset($portset2);
       $portitem10->setName('Biography');
       $portitem10->setDescription('A short bio of the author');
       $portitem10->setSortorder(1);
       
       $portitem11  = new Portitem();
       $portitem11->setPortset($portset2);
       $portitem11->setName('Open');
       $portitem11->setDescription('');
       $portitem11->setSortorder(2);
       
       $portitem12  = new Portitem();
       $portitem12->setPortset($portset2);
       $portitem12->setName('Open');
       $portitem12->setDescription('');
       $portitem12->setSortorder(3);
       
       
       $portitem13  = new Portitem();
       $portitem13->setPortset($portset2);
       $portitem13->setName('Open');
       $portitem13->setDescription('');
       $portitem13->setSortorder(4);
       
        $manager->persist($portset);
        $manager->persist($portset2);
        $manager->persist($portitem1);
        $manager->persist($portitem2);
        $manager->persist($portitem3);
        $manager->persist($portitem4);
        $manager->persist($portitem5);
        $manager->persist($portitem6);
        $manager->persist($portitem7);
        $manager->persist($portitem8);
        $manager->persist($portitem9);
        $manager->persist($portitem10);
        $manager->persist($portitem11);
        $manager->persist($portitem12);
        $manager->persist($portitem13);
        
        $manager->flush();

        $this->addReference('portset', $portset);
       
    
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}


