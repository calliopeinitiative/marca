<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objective', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('description', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('scale','entity', array('class'=>'MarcaAssessmentBundle:Scale', 'property'=>'name','expanded'=>false,'multiple'=>false, 'label' => 'Select a Scale for this Objective','attr' => array('class' => 'form-control'),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Objective'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assessmentbundle_objectivetype';
    }
}
