<?php

namespace Marca\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('startDate')
            ->add('startTime')
            ->add('endDate')
            ->add('endTime')
            ->add('title')
            ->add('description')
        ;
    }

    public function getName()
    {
        return 'marca_calendarbundle_calendartype';
    }
}
