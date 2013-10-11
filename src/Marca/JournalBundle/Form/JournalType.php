<?php

namespace Marca\JournalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JournalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text', array('label'  => 'Title','attr' => array('class' => 'text form-control'),))
            ->add('body', 'ckeditor', array('config_name' => 'editor_default',))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\JournalBundle\Entity\Journal'
        ));
    }    

    public function getName()
    {
        return 'marca_journalbundle_journaltype';
    }
}
