<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Marca\DocBundle\Form\DataTransformer\ColorToCssTransformer;

class MarkupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('color','text', array('attr' => array('class' => 'colorpicker'),'label'  => 'Markup Color',) )
            ->add('url','text', array('label'  => 'Url (Web address) for outside resources','attr' => array('class' => 'text form-control'), 'required' => false))
            ->add('linktext','text', array('label'  => 'Display text for link to outside resources','attr' => array('class' => 'text form-control'),  'required' => false))
            ->add('mouseover', 'ckeditor', array('label'=>'Text to display for this tag','config_name' => 'editor_docs',))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
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
