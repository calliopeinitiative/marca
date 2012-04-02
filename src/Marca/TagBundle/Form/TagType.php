<?php

namespace Marca\TagBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TagType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('color')
            ->add('userid')
            ->add('icon')
            ->add('tagset','entity', array('class'=>'MarcaTagBundle:Tagset', 'property'=>'name','expanded'=>true,'multiple'=>false, )) 
        ;
    }

    public function getName()
    {
        return 'marca_tagbundle_tagtype';
    }
}
