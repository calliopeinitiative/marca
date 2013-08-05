<?php
/**
 * Form for Institution Entities 
 *
 * @author afamiglietti
 */

namespace Marca\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('payment_type', 'choice', array('choices' => array(
                '0' => 'No Payment Required',
                '1' => 'Bookstore Payment Only',
                '2' => 'In App Payment Only',
                '3' => 'Bookstore and In App Payment'
                )))
            ->add('semester_price', 'money', array(
                'divisor' => 100,
                'currency' => 'USD'
            ))
            ->add('research')
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AdminBundle\Entity\Institution'
        ));
    }

    public function getName()
    {
        return 'marca_adminbundle_institutiontype';
    }
}

