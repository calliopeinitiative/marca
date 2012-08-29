<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Marca\CourseBundle\Entity\RollRepository;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $team = $options['data'];
        $courseid = $team->getCourse()->getId();
        $builder
            ->add('name')
            ->add('description')
            ->add('rolls','entity', array('class'=>'MarcaCourseBundle:Roll'
                ,'query_builder' => function(RollRepository $rr) use ($courseid){
                $qb = $rr->createQueryBuilder('roll');
                $qb->add('where', 'roll.course = :course');
                $qb->setParameter('course', $courseid);
                return $qb;
                }
                , 'property'=>'fullname', 'multiple' => 'true', 'expanded' => 'true' ));
                
            
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Team'
        ));
    }
    
    public function getName()
    {
        return 'marca_coursebundle_teamtype';
    }
}
