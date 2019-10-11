<?php

namespace Marca\UserBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', HiddenType::class)
            ->add('lastname', HiddenType::class)    
            ->add('photo',TextType::class, array('label'  => 'Photo URL','attr' => array('class' => 'text form-control'),))
            ->add('bio', CKEditorType::class, array('config_name' => 'editor_default','label'  => 'Tell us a little about youself.',))
            ->add('institution', EntityType::class, array('class'=>'MarcaAdminBundle:Institution','choice_label'=>'name', 'label'=>'Your Institution', 'disabled'=>true,'attr' => array('class' => 'text form-control'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\UserBundle\Entity\User'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_userbundle_usertype';
    }
}
