<?php

namespace Marca\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, array('label'  => 'Title','attr' => array('class' => 'text form-control'),))
            ->add('body', 'ckeditor', array('config_name' => 'editor_default',))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\ForumBundle\Entity\Forum'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_forumbundle_forumtype';
    }
}
