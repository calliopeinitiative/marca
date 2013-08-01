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
            ->add('grade')
            ->add('notes')
            ->add('created')
            ->add('updated')
            ->add('file')
            ->add('course')
            ->add('reviewer')
            ->add('reviewrubric')
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
