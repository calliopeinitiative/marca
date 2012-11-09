<?php

namespace Marca\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo', 'text', array('label'  => 'Photo URL','attr' => array('class' => 'span5'),))
            ->add('bio', 'textarea', array('label'  => 'Tell us a little about youself.',))
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
