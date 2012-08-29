<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MarkupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('function')
            ->add('value')
            ->add('color')    
            ->add('markupset')
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\DocBundle\Entity\Markup'
        ));
    }

    public function getName()
    {
        return 'marca_docbundle_markuptype';
    }
}
