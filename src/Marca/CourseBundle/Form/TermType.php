<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termName','text', array('label'  => 'Term','attr' => array('class' => 'text form-control'),))
            ->add('term', null, array('label' => 'Term ID Number (optional)', 'required' => 'false','attr' => array('class' => 'text form-control'),))
            ->add('status', 'choice', array('choices' => array(
                '0' => 'Inactive',
                '1' => 'Active',
                '2' => 'Continuing',
                '3' => 'Hidden'),'attr' => array('class' => 'form-control'),))
            ->add('institution', 'entity', array('class'=>'MarcaAdminBundle:Institution','property'=>'name', 'label'=>'Select Your Institution','attr' => array('class' => 'form-control'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Term'
        ));
    }

    public function getName()
    {
        return 'marca_coursebundle_termtype';
    }
}
