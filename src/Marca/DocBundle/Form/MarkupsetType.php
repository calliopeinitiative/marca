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
            ->add('name')
            ->add('shared', 'choice', array('choices'   => array(0 => 'My Classes', 1 => 'All Classes'),'required'  => true,'label'  => 'Shared your tagset', 'expanded' => true,'attr' => array('class' => 'checkbox inline'),))   
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
