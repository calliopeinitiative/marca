<?php

namespace Marca\GradebookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('date', DateType::class, array('widget' => 'single_text','format' => 'MM/dd/yyyy','label'  => 'Date','attr' => array('class' => 'text form-control')))
            ->add('type', ChoiceType::class, array('choices'   => array('0' => 'Absent', '1' => 'Tardy'),'required'  => true, 'multiple'=>false,'label'  => '', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\GradebookBundle\Entity\Attendance'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'marca_gradebookbundle_attendance';
    }
}
