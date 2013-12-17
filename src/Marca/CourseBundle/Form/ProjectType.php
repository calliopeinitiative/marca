<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'  => 'Name:','attr' => array('class' => 'text form-control'),))
            ->add('sortOrder', 'hidden')
            ->add('resource', 'choice', array('choices'   => array(true => 'Yes', false => 'No'),'required'  => true,'label'  => 'Resource', 'expanded' => true,'attr' => array('class' => 'radio'),))
            ->add('coursehome', 'choice', array('choices'   => array(true => 'Yes', false => 'No'),'required'  => true,'label'  => 'Show on course home:', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Project'
        ));
    }

    public function getName()
    {
        return 'marca_coursebundle_projecttype';
    }
}
