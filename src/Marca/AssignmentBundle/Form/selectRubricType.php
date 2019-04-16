<?php
/**
 * Form for selecting review rubrics  
 *
 * @author afamiglietti
 */

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class selectRubricType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //    ->add('rubric', 'entity', array('class'=>'MarcaAssignmentBundle:ReviewRubric', 'property'=>'name'))
              ->add('rubric', IntegerType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\ReviewRubric'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assignmentbundle_selectrubrictype';
    }
}