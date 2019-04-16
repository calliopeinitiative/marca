<?php

namespace Marca\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', 'ckeditor', array('config_name' => 'editor_default', 'label'  => false,))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\ForumBundle\Entity\Comment'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_forumbundle_commenttype';
    }
}
