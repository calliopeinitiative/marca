<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'  => 'Name:','attr' => array('class' => 'text form-control'),))
            ->add('sortOrder', HiddenType::class)
            ->add('resource', HiddenType::class)
            ->add('coursehome', ChoiceType::class, array('choices'   => array(true => 'Yes', false => 'No'),'required'  => true,'label'  => 'Show on course home:', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Project'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_coursebundle_projecttype';
    }
}
