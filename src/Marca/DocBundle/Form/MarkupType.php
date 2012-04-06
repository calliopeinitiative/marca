<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MarkupType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('function')
            ->add('value')
            ->add('set')
        ;
    }

    public function getName()
    {
        return 'marca_docbundle_markuptype';
    }
}
