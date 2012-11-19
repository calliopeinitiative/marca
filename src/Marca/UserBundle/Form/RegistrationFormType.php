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
            $builder->add('Firstname', null, array('label'=>'First Name', 'required'=>true));
            $builder->add('Lastname', null, array('label'=>'Last Name', 'required'=>true));
            $builder->add('share_email', null, array('label' => 'Do you want to be added to the MARCA mailing list?', 'required' => false));
            
            
        }

        public function getName()
        {
            return 'marca_user_registration';
        }
    
}


