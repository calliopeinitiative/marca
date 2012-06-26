<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PortitemType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('sortorder')
        ;
    }

    public function getName()
    {
        return 'marca_portfoliobundle_portitemtype';
    }
}
