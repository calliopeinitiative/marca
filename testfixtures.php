<?php
namespace Marca\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Marca\DocBundle\Entity\Markupset;
use Marca\DocBundle\Entity\Markup;

class LoadTestData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $markupset1 = $this->createMarkupSet('Rhetoric', 1, $user);

        $manager->persist($markupset1);
        $manager->flush();
    }
}
