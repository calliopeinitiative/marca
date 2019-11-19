<?php

namespace Marca\AssignmentBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
            ->add('description', CKEditorType::class, array('config_name' => 'editor_simple',))
            ->add('instructions', CKEditorType::class, array('config_name' => 'editor_simple',))
            ->add('keywords',TextType::class, array('attr' => array('class' => 'text form-control'),))
            ->add('shared', ChoiceType::class, array('choices'   => array('My Classes' => '0', 'Public' => '1'),'required'  => true,'label'  => 'Share your Rubric', 'expanded' => true,'attr' => array('class' => 'radio'),))
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
