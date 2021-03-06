<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    protected $options;

    public function __construct (array $options)
    {
        $this->options = $options;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->options;
        $builder
            ->add('reviewresponses', 'collection', array(
                'type'   => new ReviewResponseType($options),
                'options'  => array(
                'required'  => false),))
            ->add('grade','text', array('attr' => array('class' => 'text form-control'),))
            ->add('notes', 'textarea', array('attr' => array('class' => 'text form-control'),))
            ->add('feedbackGrade','text', array('attr' => array('class' => 'text form-control'),))
            ->add('feedbackComment', 'textarea', array('attr' => array('class' => 'text form-control'),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
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
