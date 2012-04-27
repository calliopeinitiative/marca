<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('userid')
            ->add('courseid')
            ->add('fileid')
            ->add('portOrder')
        ;
    }

    public function getName()
    {
        return 'marca_portfoliobundle_portfoliotype';
    }
}
