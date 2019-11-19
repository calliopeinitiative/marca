<?php

namespace Marca\CalendarBundle\Form;

use Marca\CalendarBundle\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, array('label'  => 'Title','attr' => array('class' => 'text form-control'),))
            ->add('startTime', TimeType::class, array('widget' => 'single_text','label'=>'Time','attr' => array('class' => 'text form-control')))
            ->add('startDate', DateType::class, array('widget' => 'single_text','format' => 'MM/dd/yyyy','label'  => 'Date','attr' => array('class' => 'text form-control')))
            ->add('color',TextType::class, array('attr' => array('class' => 'colorpicker'),'label'  => 'Event Color','attr' => array('class' => 'text form-control')) )
            ->add('description', CKEditorType::Class, ['config_name' => 'editor_default'])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
