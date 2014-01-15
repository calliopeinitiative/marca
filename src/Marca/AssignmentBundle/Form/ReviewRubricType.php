<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewRubricType extends AbstractType
{   
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('instructions')
            ->add('keywords')
            ->add('shared', 'choice', array('choices'   => array(0 => 'My Classes', 1 => 'Public'),'required'  => true,'label'  => 'Share your Rubric', 'expanded' => true,'attr' => array('class' => 'checkbox inline'),))   
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\ReviewRubric'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_reviewrubrictype';
    }
}
