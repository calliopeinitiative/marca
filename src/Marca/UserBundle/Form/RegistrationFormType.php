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
            $builder->add('Firstname', null, array('label'=>'First Name', 'required'=>true,'attr' => array('class' => 'text form-control'),));
            $builder->add('Lastname', null, array('label'=>'Last Name', 'required'=>true,'attr' => array('class' => 'text form-control'),));
            $builder->add('share_email', null, array('label' => 'Do you want to be added to our mailing list?', 'required' => false,'attr' => array('class' => 'checkbox'),));
            $builder->add('institution', 'entity', array('attr' => array('class' => 'text form-control'),
                'class' => 'MarcaAdminBundle:Institution',
                'property' => 'name'
            ));
            
        }

        public function getName()
        {
            return 'marca_user_registration';
        }
    
}


