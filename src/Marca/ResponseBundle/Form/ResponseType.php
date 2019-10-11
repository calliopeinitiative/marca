<?php

namespace Marca\ResponseBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', CKEditorType::class, array('config_name' => 'editor_simple',))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\ResponseBundle\Entity\Response'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_responsebundle_responsetype';
    }
}
