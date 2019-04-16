<?php

namespace Marca\CourseBundle\Form;

use Marca\CourseBundle\Entity\TermRepository;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
            ->add('tagset','entity', array('class'=>'MarcaTagBundle:Tagset', 'query_builder' => function(TagsetRepository $tr) use ($userid){
            $qb = $tr->createQueryBuilder('MarcaTagBundle:Tagset');
            $qb->select('t')->from('Marca\TagBundle\Entity\Tagset', 't')->innerJoin('t.user', 'u')->where('u.id = ?1')->orWhere('t.shared = 2')->setParameter('1', $userid);
             return $qb;
            }
            ,'choice_label'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select label sets','attr' => array('class' => 'checkbox'),))
            ->add('markupsets','entity', array('class'=>'MarcaDocBundle:Markupset', 'query_builder' => function(MarkupsetRepository $mr) use ($userid){
            $qb = $mr->createQueryBuilder('MarcaDocBundle:Markupset');
            $qb->select('m')->from('Marca\DocBundle\Entity\Markupset', 'm')->innerJoin('m.users', 'u')->where('u.id = ?1')->orWhere('m.shared = 2')->orWhere('m.shared = 1')->setParameter('1', $userid);
            return $qb;
            }
            ,'choice_label'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select markup sets for Documents','attr' => array('class' => 'checkbox'),));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Course'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_coursebundle_coursetype';
    }
}
