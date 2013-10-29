<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                'options'  => array(
                'required'  => false),))
            ->add('grade')
            ->add('grade','integer', array('label'  => 'Grade (must be an integer)','attr' => array('class' => 'inline'),))                
            ->add('notesforstudent','textarea', array('label'  => 'Notes for the student','attr' => array('class' => 'inline'),))  
            ->add('notesforreviewer','textarea', array('label'  => 'Notes for the reviewer','attr' => array('class' => 'inline'),))                   
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
