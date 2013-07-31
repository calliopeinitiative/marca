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
            ->add('timeCreated')
            ->add('timeModified')
            ->add('responseText')
            ->add('responseBool')
            ->add('responseInt')
            ->add('reviewDoc')
            ->add('reviewer')
            ->add('reviewPrompt')
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
