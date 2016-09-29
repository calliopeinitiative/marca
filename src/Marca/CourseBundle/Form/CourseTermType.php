<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Marca\CourseBundle\Entity\TermRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;

class CourseTermType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
            
            ->add('term','entity', array('class'=>'MarcaCourseBundle:Term', 'query_builder' => function(TermRepository $tr) use ($institutionid){
            $qb = $tr->createQueryBuilder('MarcaCourseBundle:Term');
            $qb->select('t')->from('Marca\CourseBundle\Entity\Term', 't')->innerJoin('t.institution', 'i')->where('t.status != 3')->andWhere('i.id = ?1')->setParameter('1', $institutionid);
            return $qb;
            }
            ,'choice_label'=>'termName','expanded'=>true,'multiple'=>false, 'label' => 'Term','attr' => array('class' => 'radio'),))
            
                       
   
    ;}
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Course'
        ));
    }

    public function getName()
    {
        return 'marca_coursebundle_coursetype';
    }
}
