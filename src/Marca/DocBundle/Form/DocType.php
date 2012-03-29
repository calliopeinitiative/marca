<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DocType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('body')
        ;
    }

    public function getName()
    {
        return 'marca_docbundle_doctype';
    }
}
