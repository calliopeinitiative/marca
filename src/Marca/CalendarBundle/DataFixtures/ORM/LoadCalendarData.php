<?php

namespace Marca\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\CalendarBundle\Entity\Calendar;

/**
 * LoadInstitutionData loads an institution into the database
 *
 * @author Harrison-M
 */
class LoadCalendarData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $calendar = new Calendar();
        $calendar->setStartDate(
            new \DateTime('10 am')
        );
        $calendar->setEndDate(
            new \DateTime('11 am')
        );
        $calendar->setStartTime(
            new \DateTime('10 am')
        );
        $calendar->setEndTime(
            new \DateTime('11 am')
        );

        $calendar->setTitle("Event");
        $calendar->setDescription("This is a test event");

        $calendar->setUser(
            $manager->merge($this->getReference('instr1-user'))
        );

        $calendar->setCourse(
            $manager->merge($this->getReference('course1.1'))
        );

        $manager->persist($calendar);
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


