<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TermType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('term')
            ->add('termName')
            ->add('status')
        ;
    }

    public function getName()
    {
        return 'marca_coursebundle_termtype';
    }
}
