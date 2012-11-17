<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Marca\DocBundle\Form\DataTransformer\ColorToCssTransformer;

class MarkupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entitymanager = $options['em'];
        $transformer = new ColorToCssTransformer($entitymanager);
        $builder
            ->add('name')
            ->add('value')
            ->add($builder->create('color', 'text')->addModelTransformer($transformer))
            ->add('url')
            ->add('mouseover')   
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\DocBundle\Entity\Markup'
        ));
        $resolver->setRequired(array('em'));
    }

    public function getName()
    {
        return 'marca_docbundle_markuptype';
    }
}
