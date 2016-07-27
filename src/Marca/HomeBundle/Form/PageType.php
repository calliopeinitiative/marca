<?php

namespace Marca\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text', array('label'  => 'Page title','attr' => array('class' => 'text form-control'),))
            ->add('body', 'ckeditor', array('config_name' => 'editor_page',))
            ->add('type', 'choice', array('choices'   => array(0 => 'Homepage', 1 => 'Consent', 2 => 'Rubric', 3 => 'Course Creation'),'required'  => true,'label'  => 'Choose placement for this page', 'expanded' => true,'attr' => array('class' => 'radio'),))


        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
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
