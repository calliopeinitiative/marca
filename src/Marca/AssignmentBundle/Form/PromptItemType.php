<?php

namespace Marca\AssignmentBundle\Form;

use Marca\AssignmentBundle\Entity\PromptItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromptItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prompt', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('helpText', 'ckeditor', array('config_name' => 'editor_simple',))
            ->add('type', ChoiceType::class, array('choices'   => array(PromptItem::TYPE_SHORTTEXT => 'Short Text', PromptItem::TYPE_PARAGRAPHTEXT => 'Paragraph Text', PromptItem::TYPE_SCALE => 'Scale', PromptItem::TYPE_NORESPONSE => 'No Response'),'required'  => true,'label'  => 'Response Type', 'expanded' => true,'attr' => array('class' => 'radio'),))
            ->add('scale','entity',array('class'=>'MarcaAssessmentBundle:Scale','property'=>'name', 'expanded' => true,'required'  => true,'attr' => array('class' => 'radio'),))        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\PromptItem'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assignmentbundle_promptitemtype';
    }
}
