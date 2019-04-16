<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewRubricType extends AbstractType
{   
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('instructions', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('keywords',TextType::class, array('attr' => array('class' => 'text form-control'),))
            ->add('shared', ChoiceType::class, array('choices'   => array(0 => 'My Classes', 1 => 'Public'),'required'  => true,'label'  => 'Share your Rubric', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\ReviewRubric'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assignmentbundle_reviewrubrictype';
    }
}
