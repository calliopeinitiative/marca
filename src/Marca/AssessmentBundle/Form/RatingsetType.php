<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingsetType extends AbstractType
{
    private $options;

    public function __construct($options)
    {
        $this->options= $options;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->options;
        $scale = $options['scale'];
        $builder
            ->add('ratings', 'collection', array(
                'type'   => new RatingType($scale),
                'options'  => array('required'  => false),))
            ->add('grade','integer', array('label'  => 'Grade (must be an integer)','attr' => array('class' => 'text form-control'),))
            ->add('notesforstudent','textarea', array('label'  => 'Notes for the student','attr' => array('class' => 'text form-control'),))
            ->add('notesforreviewer','textarea', array('label'  => 'Notes for the reviewer','attr' => array('class' => 'text form-control'),))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Ratingset'
        ));
    }

    public function getName()
    {
        return 'marca_assessmentbundle_ratingsettype';
    }
}
