<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewResponseType extends AbstractType
{
    protected $options;

    public function __construct (array $options)
    {
        $this->options = $options;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->options;
        $builder
            ->add('responseShortText','text', array('attr' => array('class' => 'text form-control'),'label'  => ' ',))
            ->add('responseParagraphText', 'ckeditor', array('config_name' => 'editor_simple','label'  => ' ',))
            ->add('responseBool', 'choice', array('choices'   => array('false' => 'False', 'true' => 'True'),'required'  => true, 'expanded' => true,'attr' => array('class' => 'radio'), 'label'  => ' ',))
            ->add('responseInt', 'entity', array('class' => 'MarcaAssessmentBundle:Scale','property'=>'name','query_builder' =>
                function(\Marca\AssessmentBundle\Entity\ScaleRepository $sc) use ($options) {
                    return $sc->createQueryBuilder('s')
                        ->join("s.promptitem", 'p')
                        ->join("p.responses", 'r')
                        ->where('s.id = :id')
                        ->setParameter('id', '2') ;},
                'expanded'=>true,'label'  => 'Select', 'expanded' => true,'attr' => array('class' => 'radio'),))
            ->add('helpful', 'choice', array('choices'   => array(true => 'Yes', false => 'No'),'required'  => TRUE,'label'  => 'Was this helpful?', 'expanded' => true,'attr' => array('class' => 'radio inline'),))
    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\ReviewResponse'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_reviewresponsetype';
    }
}
