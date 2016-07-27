<?php

namespace Marca\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewuserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, array('label'=>'First Name', 'required'=>true,'attr' => array('class' => 'text form-control'),))
            ->add('lastname', null, array('label'=>'Last Name', 'required'=>true,'attr' => array('class' => 'text form-control'),))
            ->add('photo', 'text', array('label'  => 'Photo URL','attr' => array('class' => 'text form-control'),))
            ->add('bio', 'ckeditor', array('config_name' => 'editor_default','label'  => 'Tell us a little about youself.',))
            ->add('institution', 'entity', array('class'=>'MarcaAdminBundle:Institution','property'=>'name', 'label'=>'Select Your Institution'))    
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
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
