<?php

namespace Marca\CourseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termName',TextType::class, array('label'  => 'Term','attr' => array('class' => 'text form-control'),))
            ->add('term', TextType::class, array('label' => 'Term ID Number (optional)', 'required' => 'false','attr' => array('class' => 'text form-control'),))
            ->add('status', ChoiceType::class, array('choices' => array(
                'Inactive' => '0',
                'Active' => '1',
                'Continuing' => '2',
                'Hidden' => '3'),'attr' => array('class' => 'form-control'),))
            ->add('institution', EntityType::class, array('class'=>'MarcaAdminBundle:Institution','choice_label'=>'name', 'label'=>'Select Your Institution','attr' => array('class' => 'form-control'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Term'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_coursebundle_termtype';
    }
}
