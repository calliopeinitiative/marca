<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('reviewresponses', CollectionType::class, array(
                'type'   => new ReviewResponseType($options),
                'options'  => array(
                'required'  => false),))
            ->add('grade',TextType::class, array('attr' => array('class' => 'text form-control'),))
            ->add('notes', TextareaType::class, array('attr' => array('class' => 'text form-control'),))
            ->add('feedbackGrade',TextType::class, array('attr' => array('class' => 'text form-control'),))
            ->add('feedbackComment', TextareaType::class, array('attr' => array('class' => 'text form-control'),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\Review'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assignmentbundle_reviewtype';
    }
}
