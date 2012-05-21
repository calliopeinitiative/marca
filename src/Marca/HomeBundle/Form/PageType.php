<?php

namespace Marca\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('body')
        ;
    }

    public function getName()
    {
        return 'marca_homebundle_pagetype';
    }
}
