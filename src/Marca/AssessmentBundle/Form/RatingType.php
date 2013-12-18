<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Marca\AssessmentBundle\Entity\ScaleitemRepository;

class RatingType extends AbstractType
{
    private $scale;

    public function __construct($scale)
    {
        $this->scale= $scale;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $scale = $this->scale;
        $builder
           ->add('scaleitem','entity', array('class'=>'MarcaAssessmentBundle:Scaleitem', 'query_builder' => function(ScaleitemRepository $sr)use ($scale) {
            $qb = $sr->createQueryBuilder('MarcaAssessmentBundle:Scaleitem');
            $qb->select('s')->from('Marca\AssessmentBundle\Entity\Scaleitem', 's')->where('s.scale = ?1')->setParameter('1', $scale);
            return $qb;
            }
            ,'property'=>'name','expanded'=>true,'multiple'=>false, 'label' => '  ','attr' => array('class' => 'radio'),))
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
