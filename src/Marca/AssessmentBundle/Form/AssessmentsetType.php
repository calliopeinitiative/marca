<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AssessmentsetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('name','text', array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_default',))
            ->add('shared', 'choice', array('choices'   => array(0 => 'My Classes', 1 => 'All Classes', 2 => 'Default'),'required'  => true,'label'  => 'Share your Assessment set?', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Assessmentset'
        ));
    }

    public function getName()
    {
        return 'marca_assessmentbundle_assessmentsettype';
    }
}
