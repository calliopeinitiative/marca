<?php

namespace Marca\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('body')
            ->add('parent','hidden')     
        ;
    }

    public function getName()
    {
        return 'marca_forumbundle_commenttype';
    }
}
