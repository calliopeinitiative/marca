<?php

namespace Marca\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('research', ChoiceType::class, array('choices'   => array('Yes' => 1, 'No' => 2),'required'  => true,'label'  => 'I agree to participate in this research.', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\UserBundle\Entity\User'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_userbundle_researchtype';
    }
}