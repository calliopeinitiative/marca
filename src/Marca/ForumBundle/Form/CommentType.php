<?php

namespace Marca\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('parent')
            ->add('body')
            ->add('created')
            ->add('updated')
            ->add('forum')
            ->add('user')
        ;
    }

    public function getName()
    {
        return 'marca_forumbundle_commenttype';
    }
}
