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
            ->add('firstname', 'hidden')
            ->add('lastname', 'hidden')    
            ->add('photo', 'text', array('label'  => 'Photo URL','attr' => array('class' => 'span5'),))
            ->add('bio', 'textarea', array('label'  => 'Tell us a little about youself.',))
            ->add('institution', 'entity', array('class'=>'MarcaAdminBundle:Institution','property'=>'name', 'label'=>'Select Your Institution'))        
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
