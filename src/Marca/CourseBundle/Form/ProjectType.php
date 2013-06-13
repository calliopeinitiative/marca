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
            ->add('name')
            ->add('sortOrder', 'hidden')
            ->add('resource', 'choice', array('choices'   => array(true => 'Unit', false => 'Folder'),'required'  => true,'label'  => 'Change type:', 'expanded' => true,'attr' => array('class' => 'checkbox inline'),))
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
