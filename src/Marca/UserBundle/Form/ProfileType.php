<?php

namespace Marca\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('email')
        ;
    }

    public function getName()
    {
        return 'marca_userbundle_profiletype';
    }
}
