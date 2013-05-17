<?php

namespace Marca\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('homepage')    
            ->add('title')
            ->add('body')
            
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\HomeBundle\Entity\Page'
        ));
    }

    public function getName()
    {
        return 'marca_homebundle_pagetype';
    }
}
