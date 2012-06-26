<?php

namespace Marca\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('startDate', 'date', array('widget' => 'single_text','format' => 'MM/dd/yyyy')) 
            ->add('startTime', 'time', array('widget' => 'single_text', 'attr' => array('name' => 'timepicker'))) 
            ->add('endDate', 'date', array('widget' => 'single_text','format' => 'MM/dd/yyyy')) 
            ->add('endTime', 'time', array('widget' => 'single_text', 'attr' => array('name' => 'timepicker')))                 
            ->add('title')
            ->add('description')
        ;
    }

    public function getName()
    {
        return 'marca_calendarbundle_calendartype';
    }
}
