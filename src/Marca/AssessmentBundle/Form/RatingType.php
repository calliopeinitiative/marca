<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Marca\AssessmentBundle\Entity\ScaleitemRepository;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
    
           ->add('scaleitem','entity', array('class'=>'MarcaAssessmentBundle:Scaleitem', 'query_builder' => function(ScaleitemRepository $sr) {
            $qb = $sr->createQueryBuilder('MarcaAssessmentBundle:Scaleitem');
            $qb->select('s')->from('Marca\AssessmentBundle\Entity\Scaleitem', 's')->where('s.scale = ?1')->setParameter('1', '1');
            return $qb;
            }
            ,'property'=>'name','expanded'=>true,'multiple'=>false, 'label' => '  ','attr' => array('class' => 'checkbox inline pad1'),))
            ;    
            
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Rating'
        ));
    }

    public function getName()
    {
        return 'marca_assessmentbundle_ratingtype';
    }
}
