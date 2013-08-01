<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Marca\AssignmentBundle\Entity\PromptItem;

class PromptItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prompt')
            ->add('helpText')
            ->add('type', 'choice', array('choices'   => array(PromptItem::TYPE_SHORTTEXT => 'Short Text', PromptItem::TYPE_PARAGRAPHTEXT => 'Paragraph Text', PromptItem::TYPE_SCALE => 'Scale', PromptItem::TYPE_NORESPONSE => 'No Response'),'required'  => true,'label'  => 'Response Type', 'expanded' => true,'attr' => array('class' => 'checkbox inline'),))   
            ->add('scale','entity',array('class'=>'MarcaAssessmentBundle:Scale','property'=>'name'))        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\PromptItem'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_promptitemtype';
    }
}
