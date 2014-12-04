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
            ->add('description', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('shared', 'choice', array('choices'   => array(0 => 'My Classes', 1 => 'All Classes', 2 => 'Default'),'required'  => true,'label'  => 'Share your Portfolio set?', 'expanded' => true,'attr' => array('class' => 'radio'),))
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
