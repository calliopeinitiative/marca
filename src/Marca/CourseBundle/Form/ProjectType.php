<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'  => 'Name:','attr' => array('class' => 'text form-control'),))
            ->add('sortOrder', 'hidden')
            ->add('resource', 'hidden')
            ->add('coursehome', 'hidden')
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Project'
        ));
    }

    public function getName()
    {
        return 'marca_coursebundle_projecttype';
    }
}
