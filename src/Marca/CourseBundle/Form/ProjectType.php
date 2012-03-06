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
            ->add('userid')
            ->add('courseid')
            ->add('sortOrder')
            ->add('resource')
        ;
    }

    public function getName()
    {
        return 'marca_coursebundle_projecttype';
    }
}
