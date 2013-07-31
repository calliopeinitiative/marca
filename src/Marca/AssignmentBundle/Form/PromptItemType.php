<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PromptItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prompt')
            ->add('helpText')
            ->add('type')
            ->add('scale')
            ->add('reviewRubric')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\PromptItem'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_promptitemtype';
    }
}
