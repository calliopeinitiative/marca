<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('sortOrder', 'hidden')
            ->add('resource', 'choice', array('choices'   => array(true => 'Yes', false => 'No'),'required'  => true,'label'  => 'Display in Resources', 'expanded' => true,))
        ;
    }

    public function getName()
    {
        return 'marca_coursebundle_projecttype';
    }
}
