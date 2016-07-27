<?php

namespace Marca\AssessmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScaleitemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('value','text', array('label'  => 'Value','attr' => array('class' => 'text form-control'),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Scaleitem'
        ));
    }

    public function getName()
    {
        return 'marca_assessmentbundle_scaleitemtype';
    }
}
