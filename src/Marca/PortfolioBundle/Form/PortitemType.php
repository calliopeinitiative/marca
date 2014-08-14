<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PortitemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'  => 'Portfolio item name','attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_default',))
            ->add('sortorder','number', array('label'  => 'Sort Order','attr' => array('class' => 'text form-control'),))
            ->add('status', 'choice', array('choices'   => array(1 => 'Yes', 0 => 'No'),'required'  => true,'label'  => 'Show in Portfolio Display', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\PortfolioBundle\Entity\Portitem'
        ));
    }

    public function getName()
    {
        return 'marca_portfoliobundle_portitemtype';
    }
}
