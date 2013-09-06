<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnnounceType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('announcement', 'ckeditor', array('config_name' => 'editor_default',))
                
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Course'
        ));
    }

    public function getName()
    {
        return 'marca_coursebundle_announcetype';
    }
}
