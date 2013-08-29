<?php

namespace Marca\DocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Marca\DocBundle\Form\DataTransformer\ColorToCssTransformer;

class MarkupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entitymanager = $options['em'];
        $transformer = new ColorToCssTransformer($entitymanager);
        $builder
            ->add('name', null, array('label'=>'Tag Name'))
            ->add('color','text', array('attr' => array('class' => 'inline'),'label'  => 'Markup Color',) ) 
            ->add('url', null, array('label'=>'Url (Web address) for outside resources'))
            ->add('linktext', null, array('label'=>'Display text for link to outside resources'))    
            ->add('mouseover', null, array('label'=>'Text to display for this tag'))   
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\DocBundle\Entity\Markup'
        ));
        $resolver->setRequired(array('em'));
    }

    public function getName()
    {
        return 'marca_docbundle_markuptype';
    }
}
