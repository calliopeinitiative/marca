<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TermType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termName', null, array('label' => 'Term') )
            ->add('term', null, array('label' => 'Term ID Number (optional)', 'required' => 'false'))
            ->add('status', 'choice', array('choices' => array(
                '0' => 'Inactive',
                '1' => 'Active',
                '2' => 'Continuing')))
            ->add('institution', 'entity', array('class'=>'MarcaAdminBundle:Institution','property'=>'name', 'label'=>'Select Your Institution'))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
