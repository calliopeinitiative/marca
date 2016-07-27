<?php

namespace Marca\GradebookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GradeType extends AbstractType
{

    protected $options;

    public function __construct (array $options)
    {
        $this->options = $options;
    }

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->options;
        $builder
            ->add('grade','number', array('attr' => array('class' => 'form-control'),))
            ->add('category', 'entity', array('class' => 'MarcaGradebookBundle:Category','property'=>'name','query_builder' =>
                function(\Marca\GradebookBundle\Entity\CategoryRepository $er) use ($options) {
                    $gradesetid = $options['gradesetid'] ;
                    return $er->createQueryBuilder('c')
                        ->where('c.gradeset = :gradeset')
                        ->setParameter('gradeset', $gradesetid);}, 'expanded'=>true,'label'  => 'Select Grade Category', 'required'  => true,'attr' => array('class' => 'radio'),))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\GradebookBundle\Entity\Grade'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'marca_gradebookbundle_grade';
    }
}
