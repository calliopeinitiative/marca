<?php

namespace Marca\DocBundle\DataFixtures\ORM\AdditionalFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Marca\AdminBundle\Entity\Coupon;
use Marca\CourseBundle\Entity\Term;
use Doctrine\ORM\EntityManager;

/**
 * Description of LoadMarkupsetData
 * To load this fixture  only path, not filename
 * app/console doctrine:fixtures:load --fixtures="pathtofile" --append
 *
 * @author Ron
 */
class LoadCouponData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

$id =3;
$term = $manager->getRepository('MarcaCourseBundle:Term')->findOneById($id);
if (($handle = fopen(dirname(__FILE__)."/codes.csv", "r")) !== FALSE){
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
        $coupon = new Coupon();
        $coupon->setCode($data[0]);
        $coupon->setTerm($term); 

        $manager->persist($coupon);
        $manager->flush();
        }
    } 
  }    
}


