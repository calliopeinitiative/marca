<?php

namespace Marca\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, array('label'  => 'Title','attr' => array('class' => 'text form-control'),))
            ->add('startTime', TimeType::class, array('widget' => 'single_text','label'=>'Time','attr' => array('class' => 'text form-control')))

            ->add('startDate', DateType::class, array('widget' => 'single_text','format' => 'MM/dd/yyyy','label'  => 'Date','attr' => array('class' => 'text form-control')))
            ->add('color',TextType::class, array('attr' => array('class' => 'colorpicker'),'label'  => 'Event Color',) )
            ->add('description', 'ckeditor', array('config_name' => 'editor_default',))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CalendarBundle\Entity\Calendar'
        ));
    } 
    
    public function getBlockPrefix()
    {
        return 'marca_calendarbundle_calendartype';
    }
}
