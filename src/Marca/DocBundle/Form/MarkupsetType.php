<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarkupsetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_docs',))
            ->add('sortorder', HiddenType::class)
            ->add('shared', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\DocBundle\Entity\Markupset'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_docbundle_markupsettype';
    }
}
