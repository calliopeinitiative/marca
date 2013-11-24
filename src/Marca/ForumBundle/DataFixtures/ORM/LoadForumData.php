<?php

namespace Marca\ForumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\ForumBundle\Entity\Forum;

/**
 * LoadInstitutionData loads an institution into the database
 *
 * @author Harrison-M
 */
class LoadForumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $forum = new Forum();
        $forum->setTitle('Test Forum');
        $forum->setBody("This is a test forum.  Isn't it neat?");
        $forum->setUser(
            $manager->merge($this->getReference('instr1-user'))
        );
        $forum->setCourse(
            $manager->merge($this->getReference('course1.1'))
        );

        $manager->persist($forum);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6;
    }
}


