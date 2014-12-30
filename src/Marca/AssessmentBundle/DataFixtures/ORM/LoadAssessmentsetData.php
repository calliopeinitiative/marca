<?php

namespace Marca\AssessmentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\AdminBundle\Entity\Institution;
use Marca\AssessmentBundle\Entity\Assessmentset;
use Marca\AssessmentBundle\Entity\Objective;
use Marca\AssessmentBundle\Entity\Scale;
use Marca\AssessmentBundle\Entity\ScaleItem;

/**
 * LoadAssessmentsetData loads an institution into the database
 *
 * @author Harrison-M
 */
class LoadAssessmentsetData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $assessmentset = new Assessmentset();
        $assessmentset->setName('Test set');
        $assessmentset->setDescription('Test assessment set');
        $assessmentset->setShared(2); //Make default

        $scale = new Scale();
        $scale->setName("Test scale");

        $objective = new Objective();
        $objective->setObjective('Marca Dev');
        $objective->setDescription('Fixtures');
        $objective->setAssessmentset($assessmentset);
        $objective->setScale($scale);

        $scaleitem = new Scaleitem();
        $scaleitem->setName('Test scale item');
        $scaleitem->setValue(50);
        $scaleitem->setScale($scale);

        $scaleitem2 = new Scaleitem();
        $scaleitem2->setName('Test scale item 2');
        $scaleitem2->setValue(50);
        $scaleitem2->setScale($scale);

        $manager->persist($assessmentset);
        $manager->persist($scale);
        $manager->persist($objective);
        $manager->persist($scaleitem);
        $manager->persist($scaleitem2);
        $manager->flush();

        $this->addReference('assessmentset', $assessmentset);
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}


