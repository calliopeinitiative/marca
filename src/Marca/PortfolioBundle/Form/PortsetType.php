<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PortsetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label'  => 'Portfolio set name','attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_default',))
            ->add('shared', 'choice', array('choices'   => array(0 => 'My Classes', 1 => 'All Classes', 2 => 'Default'),'required'  => true,'label'  => 'Share your Portfolio set?', 'expanded' => true,'attr' => array('class' => 'radio'),))
            ->add('private_port', 'choice', array('choices'   => array(false => 'Shared Portfolios', true => 'Private Portfolios'),'required'  => true,'label'  => 'Select private portfolios (portfolios and items in portfolios are shared with instructors only) or shared portfolios (items in portfolios are shared with classmates). If you don not understand these options, choose shared portfolios', 'expanded' => true,'attr' => array('class' => 'radio'),))
            ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\PortfolioBundle\Entity\Portset'
        ));
    }

    public function getName()
    {
        return 'marca_portfoliobundle_portsettype';
    }
}
