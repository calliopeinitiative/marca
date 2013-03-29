<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ObjectiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objective')
            ->add('description')
            ->add('scale','entity', array('class'=>'MarcaAssessmentBundle:Scale', 'property'=>'name','expanded'=>false,'multiple'=>false, 'label' => 'Select a Scale for this Objective',))     
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Objective'
        ));
    }

    public function getName()
    {
        return 'marca_assessmentbundle_objectivetype';
    }
}
