<?php

namespace Marca\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;


class RegistrationFormType extends BaseType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
        {
            parent::buildForm($builder, $options);

            // add your custom field
            $builder->add('share_email', null, array('label' => 'Do you want to be added to the MARCA mailing list?'));
        }

        public function getName()
        {
            return 'marca_user_registration';
        }
    
}


