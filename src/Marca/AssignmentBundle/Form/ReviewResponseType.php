<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('responseShortText','text', array('attr' => array('class' => 'text form-control'),))
            ->add('responseParagraphText','text', array('attr' => array('class' => 'text form-control'),))
            ->add('responseBool','text', array('attr' => array('class' => 'text form-control'),))
            ->add('responseInt','text', array('attr' => array('class' => 'text form-control'),))
            ->add('helpful', 'choice', array('choices'   => array(true => 'Yes', false => 'No'),'required'  => FALSE,'label'  => 'Was this helpful?', 'expanded' => true,'attr' => array('class' => 'radio inline'),))
    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\ReviewResponse'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_reviewresponsetype';
    }
}
