<?php

namespace Marca\AssignmentBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Marca\AssignmentBundle\Entity\PromptItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromptItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prompt', CKEditorType::class, array('config_name' => 'editor_simple',))
            ->add('helpText', CKEditorType::class, array('config_name' => 'editor_simple',))
            ->add('type', ChoiceType::class, array('choices'   =>
                array(
                    'Short Text'=> PromptItem::TYPE_SHORTTEXT ,
                    'Paragraph Text' =>PromptItem::TYPE_PARAGRAPHTEXT ,
                    'Scale' => PromptItem::TYPE_SCALE,
                    'No Response'=> PromptItem::TYPE_NORESPONSE
                ),'required'  => true,'label'  => 'Response Type', 'expanded' => true,'attr' => array('class' => 'radio'),))
            ->add('scale',EntityType::class ,array('class'=>'MarcaAssessmentBundle:Scale','choice_label'=>'name', 'expanded' => true,'required'  => true,'attr' => array('class' => 'radio'),))        ;
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
