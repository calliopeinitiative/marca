<?php

namespace Marca\TagBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('color',TextType::class, array('attr' => array('class' => 'colorpicker'),'label'  => 'Color',) )
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\TagBundle\Entity\Tag'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_tagbundle_tagtype';
    }
}
