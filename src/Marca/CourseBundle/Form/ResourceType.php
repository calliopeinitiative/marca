<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'  => 'Name:','attr' => array('class' => 'text form-control'),))
            ->add('coursehome', ChoiceType::class, array('choices'   => array('No' => '0', 'Yes' => '1'),'required'  => true, 'expanded'=>true,'multiple'=>false,'label'  => 'Show on course home:','attr' => array('class' => 'radio'),))
            ->add('submit',SubmitType::class, array('label'  => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
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
