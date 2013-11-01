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
            ->add('objective', 'ckeditor', array('config_name' => 'editor_default',))
            ->add('description', 'ckeditor', array('config_name' => 'editor_default',))
            ->add('scale','entity', array('class'=>'MarcaAssessmentBundle:Scale', 'property'=>'name','expanded'=>false,'multiple'=>false, 'label' => 'Select a Scale for this Objective','attr' => array('class' => 'form-control'),))
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
