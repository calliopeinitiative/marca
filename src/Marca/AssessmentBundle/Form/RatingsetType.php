<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingsetType extends AbstractType
{
    private $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->options;
        $scale = $options['scale'];
        $role = $options['role'];
        if ($role == 4) {
            $builder->add('notesforstudent', TextareaType::class, array('disabled' => true, 'label' => 'Notes for the student', 'attr' => array('class' => 'text form-control'),));
        } else {
            $builder->add('notesforstudent', TextareaType::class, array('disabled' => false, 'label' => 'Notes for the student', 'attr' => array('class' => 'text form-control'),));
            }
        $builder
            ->add('ratings', CollectionType::class, array(
                'type' => new RatingType($scale),
                'options' => array('required' => false),))
            ->add('grade', IntegerType::class, array('label' => 'Grade (must be an integer)', 'attr' => array('class' => 'text form-control'),))
            ->add('notesforreviewer', TextareaType::class, array('label' => 'Notes for the reviewer', 'attr' => array('class' => 'text form-control'),));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Ratingset'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assessmentbundle_ratingsettype';
    }
}
