<?php

namespace Marca\CourseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\CourseBundle\Entity\Term;


/**
 * LoadInstitutionData loads an institution into the database
 *
 * @author Harrison-M
 */
class LoadTermData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $term = new Term();
        $term->setTerm('1');
        $term->setTermName('Spring 2013');
        $term->setStatus(Term::STATUS_ACTIVE);
        $term->setInstitution(
            $manager->merge($this->getReference('institution'))
        );

        $manager->persist($term);
        $manager->flush();

        $this->addReference('term', $term);
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}

?>
