<?php
/**
 * Form for selecting review rubrics  
 *
 * @author afamiglietti
 */

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class selectRubricType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //    ->add('rubric', 'entity', array('class'=>'MarcaAssignmentBundle:ReviewRubric', 'property'=>'name'))
              ->add('rubric', 'integer')
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\ReviewRubric'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_selectrubrictype';
    }
}