<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortitemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'  => 'Portfolio item name','attr' => array('class' => 'text form-control'),))
            ->add('description', 'ckeditor', array('config_name' => 'editor_default',))
            ->add('sortorder',NumberType::class, array('label'  => 'Sort Order','attr' => array('class' => 'text form-control'),))
            ->add('status', ChoiceType::class, array('choices'   => array(1 => 'Yes', 0 => 'No'),'required'  => true,'label'  => 'Show in Portfolio Display', 'expanded' => true,'attr' => array('class' => 'radio'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\PortfolioBundle\Entity\Portitem'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_portfoliobundle_portitemtype';
    }
}
