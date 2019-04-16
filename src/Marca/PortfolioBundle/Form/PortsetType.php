<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortsetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'  => 'Portfolio set name','attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('shared', ChoiceType::class, array('choices'   => array(0 => 'My Classes', 1 => 'All Classes', 2 => 'Default'),'required'  => true,'label'  => 'Share your Portfolio set?', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\PortfolioBundle\Entity\Portset'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_portfoliobundle_portsettype';
    }
}
