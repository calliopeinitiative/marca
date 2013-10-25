<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MarkupsetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_docs',))
            ->add('sortorder')     
            ->add('shared', 'choice', array('choices'   => array(0 => 'My Classes', 1 => 'All Classes', 2 => 'Default'),'required'  => true,'label'  => 'Share your  Markup Set', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\DocBundle\Entity\Markupset'
        ));
    }

    public function getName()
    {
        return 'marca_docbundle_markupsettype';
    }
}
