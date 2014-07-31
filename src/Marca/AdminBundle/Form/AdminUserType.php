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
            ->add('username_canonical','text', array('label'  => 'Username Canonical','attr' => array('class' => 'text form-control'),))
            ->add('email','text', array('label'  => 'Email','attr' => array('class' => 'text form-control'),))
            ->add('email_canonical','text', array('label'  => 'Email Canonical','attr' => array('class' => 'text form-control'),))
    ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'marca_userbundle_usertype';
    }
}
