<?php

namespace Marca\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class JournalType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('courseid')
            ->add('title')
            ->add('body')
        ;
    }

    public function getName()
    {
        return 'marca_journalbundle_journaltype';
    }
}
