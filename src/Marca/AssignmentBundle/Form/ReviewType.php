<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reviewresponses', 'collection', array(
                'type'   => new ReviewResponseType(),
                'options'  => array(
                'required'  => false),))
            ->add('grade','text', array('attr' => array('class' => 'text form-control'),))
            ->add('notes','text', array('attr' => array('class' => 'text form-control'),))
            ->add('feedbackGrade','text', array('attr' => array('class' => 'text form-control'),))
            ->add('feedbackComment','text', array('attr' => array('class' => 'text form-control'),))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\Review'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_reviewtype';
    }
}
