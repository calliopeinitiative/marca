<?php

namespace Marca\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username','text', array('label'  => 'Username','attr' => array('class' => 'text form-control'),))
            ->add('username_canonical','text', array('label'  => 'Same username lowercase','attr' => array('class' => 'text form-control'),))
            ->add('email','text', array('label'  => 'Email','attr' => array('class' => 'text form-control'),))
            ->add('email_canonical','text', array('label'  => 'Same email lowercase','attr' => array('class' => 'text form-control'),))
    ;
    }
    
    public function getName()
    {
        return 'marca_adminbundle_useradmin';
    }
}
