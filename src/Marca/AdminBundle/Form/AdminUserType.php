<?php

namespace Marca\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class, array('label'  => 'First name','attr' => array('class' => 'text form-control'),))
            ->add('lastname',TextType::class, array('label'  => 'Last name','attr' => array('class' => 'text form-control'),))
            ->add('username',TextType::class, array('label'  => 'Username','attr' => array('class' => 'text form-control'),))
            ->add('username_canonical',TextType::class, array('label'  => 'Same username lowercase','attr' => array('class' => 'text form-control'),))
            ->add('email',TextType::class, array('label'  => 'Email','attr' => array('class' => 'text form-control'),))
            ->add('email_canonical',TextType::class, array('label'  => 'Same email lowercase','attr' => array('class' => 'text form-control'),))
    ;
    }
    
    public function getBlockPrefix()
    {
        return 'marca_adminbundle_useradmin';
    }
}
