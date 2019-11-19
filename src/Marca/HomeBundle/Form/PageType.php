<?php

namespace Marca\HomeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', CKEditorType::class, array('label'  => 'Page title','attr' => array('class' => 'text form-control'),))
            ->add('body', CKEditorType::class, array('config_name' => 'editor_page',))
            ->add('type', ChoiceType::class, array('choices' => array('Homepage' => 0, 'Consent' => 1, 'Rubric' => 2, 'Course Creation' => 3),'required'  => true,'label'  => 'Choose placement for this page', 'expanded' => true,'attr' => array('class' => 'radio'),))

        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\HomeBundle\Entity\Page'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_homebundle_pagetype';
    }
}
