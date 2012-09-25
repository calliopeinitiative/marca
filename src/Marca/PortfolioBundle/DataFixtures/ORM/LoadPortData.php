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

        $manager->persist($portset);
        $manager->persist($portitem1);
        $manager->persist($portitem2);
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


