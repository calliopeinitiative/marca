<?php
/**
 * Form for Institution Entities 
 *
 * @author afamiglietti
 */

namespace Marca\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('name',TextType::class, array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('payment_type', ChoiceType::class, array('choices' => array(
                '0' => 'No Payment Required',
                '1' => 'Bookstore Payment Only',
                '2' => 'In App Payment Only',
                '3' => 'Bookstore and In App Payment'
                ),'attr' => array('class' => 'form-control'),))
            ->add('semester_price', MoneyType::class, array('divisor' => 100, 'currency' => 'USD',
            'label'  => 'Semester Price','attr' => array('class' => 'text form-control')))
            ->add('research', CheckboxType::class, array('label'  => 'Include Research Consent','attr' => array('class' => 'checkbox'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AdminBundle\Entity\Institution'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_adminbundle_institutiontype';
    }
}

