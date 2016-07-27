<?php

namespace Marca\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text', array('label'  => 'Title','attr' => array('class' => 'text form-control'),))
            ->add('startTime', 'time', array('widget' => 'single_text','label'=>'Time','attr' => array('class' => 'text form-control')))

            ->add('startDate', 'date', array('widget' => 'single_text','format' => 'MM/dd/yyyy','label'  => 'Date','attr' => array('class' => 'text form-control')))
            ->add('color','text', array('attr' => array('class' => 'colorpicker'),'label'  => 'Event Color',) )
            ->add('description', 'ckeditor', array('config_name' => 'editor_default',))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CalendarBundle\Entity\Calendar'
        ));
    } 
    
    public function getName()
    {
        return 'marca_calendarbundle_calendartype';
    }
}
