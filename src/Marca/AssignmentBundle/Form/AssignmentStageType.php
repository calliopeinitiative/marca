<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class AssignmentStageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('instructions', 'text', array('data'=>'Type assignment instructions here, or use the buttons to attach or upload a document'))->add('dueDate')->add('reviewsRequired')->add('reviewInstructions');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Marca\AssignmentBundle\Entity\AssignmentStage',));
    }

    public function getName()
    {
        return 'assignmentStage';
    }

} 