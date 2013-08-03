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
            ->add('title')
            ->add('body')
            ->add('type', 'choice', array('choices'   => array(0 => 'Homepage', 1 => 'Consent'),'required'  => true,'label'  => 'Choose placement for this page', 'expanded' => true,'attr' => array('class' => 'checkbox inline'),))


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
