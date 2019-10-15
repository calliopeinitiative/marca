<?php

namespace Marca\DocBundle\Form;

use Marca\DocBundle\Form\DataTransformer\ColorToCssTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class MarkupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('color',TextType::class, array('attr' => array('class' => 'colorpicker'),'label'  => 'Markup Color',) )
            ->add('url',TextType::class, array('label'  => 'Url (Web address) for outside resources','attr' => array('class' => 'text form-control'), 'required' => false))
            ->add('linktext',TextType::class, array('label'  => 'Display text for link to outside resources','attr' => array('class' => 'text form-control'),  'required' => false))
            ->add('mouseover', CKEditorType::class, array('label'=>'Text to display for this tag','config_name' => 'editor_docs',))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\DocBundle\Entity\Markup'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_docbundle_markuptype';
    }
}
