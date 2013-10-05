<?php

namespace Marca\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\AdminBundle\Entity\Institution;


/**
 * LoadInstitutionData loads an institution into the database
 *
 * @author Harrison-M
 */
class LoadInstitutionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $institution = new Institution();
        $institution->setName('Callilope Initiative');
        $institution->setPaymentType(1);
        $institution->setSemesterPrice(512);

        $manager->persist($institution);
        $manager->flush();

        $this->addReference('institution', $institution);
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}


