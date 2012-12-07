<?php

namespace Marca\CourseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\CourseBundle\Entity\Course;
use Marca\CourseBundle\Entity\Roll;
use Marca\CourseBundle\Entity\Project;


/*
 * By default, assigns courses to the test instructors created in the user fixture
 * if you want, you can assign one or more test courses to your own user by altering
 * the appropriate lines below
 */

/**
 * Description of LoadCourseData
 *
 * @author afamiglietti
 */
class LoadCourseData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

            $course = new Course();
            $user = $manager->merge($this->getReference('instr1-user')); 
            $course->setUser($user);
            $course->setName("Test Course 1");
            $time = new \DateTime('9 am');
            $course->setTime($time);
            $course->setStudentForum(True);


            $roll = new Roll();
            $roll->setRole(Roll::ROLE_INSTRUCTOR);
            $roll->setUser($user);
            $roll->setStatus(1);
            $roll->setCourse($course);

            $project1 = new Project();
            $project1->setName('Paper 1');
            $project1->setSortOrder(1);
            $project1->setCourse($course);

            $project2 = new Project();
            $project2->setName('Paper 2');
            $project2->setSortOrder(2);
            $project2->setCourse($course);

            $project3 = new Project();
            $project3->setName('Paper 3');
            $project3->setSortOrder(3);
            $project3->setCourse($course);

            $project4 = new Project();
            $project4->setName('Portfolio Prep');
            $project4->setSortOrder(4);
            $project4->setCourse($course);

            $course->setProjectDefault($project1);

            $manager->persist($course);
            $manager->persist($roll);
            $manager->persist($project1);
            $manager->persist($project2);
            $manager->persist($project3);
            $manager->persist($project4);
            $manager->flush();

            $this->addReference('course1.1', $course);

            $course = new Course();
            $user = $manager->merge($this->getReference('instr2-user')); 
            $course->setUser($user);
            $course->setName("Test Course 2");
            $time = new \DateTime('11 am');
            $course->setTime($time);
            $course->setStudentForum(True);


            $roll = new Roll();
            $roll->setRole(Roll::ROLE_INSTRUCTOR);
            $roll->setUser($user);
            $roll->setStatus(1);
            $roll->setCourse($course);

            $project1 = new Project();
            $project1->setName('Paper 1');
            $project1->setSortOrder(1);
            $project1->setCourse($course);

            $project2 = new Project();
            $project2->setName('Paper 2');
            $project2->setSortOrder(2);
            $project2->setCourse($course);

            $project3 = new Project();
            $project3->setName('Paper 3');
            $project3->setSortOrder(3);
            $project3->setCourse($course);

            $project4 = new Project();
            $project4->setName('Portfolio Prep');
            $project4->setSortOrder(4);
            $project4->setCourse($course);

            $course->setProjectDefault($project1);

            $manager->persist($course);
            $manager->persist($roll);
            $manager->persist($project1);
            $manager->persist($project2);
            $manager->persist($project3);
            $manager->persist($project4);
            $manager->flush();

            $this->addReference('course2.1', $course);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}


