<?php

namespace Marca\AssessmentBundle\Form;

use Marca\AssignmentBundle\Form\ReviewResponseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingsetType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options['options'];
        $role = $this->options['role'] ;
        $scale = $this->options['scale'] ;
        if ($role == 4) {
            $builder->add('notesforstudent', TextareaType::class, array('disabled' => true, 'label' => 'Notes for the student', 'attr' => array('class' => 'text form-control'),));
        } else {
            $builder->add('notesforstudent', TextareaType::class, array('disabled' => false, 'label' => 'Notes for the student', 'attr' => array('class' => 'text form-control'),));
            }
        $builder
            ->add('ratings', CollectionType::class, [
                'entry_type' => RatingType::class, 'entry_options' => ['options' => $scale],
            ])
            ->add('grade', IntegerType::class, array('label' => 'Grade (must be an integer)', 'attr' => array('class' => 'text form-control'),))
            ->add('notesforreviewer', TextareaType::class, array('label' => 'Notes for the reviewer', 'attr' => array('class' => 'text form-control'),));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Ratingset',
            'options' => null,
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assessmentbundle_ratingsettype';
    }
}
