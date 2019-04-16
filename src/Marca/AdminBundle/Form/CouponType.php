<?php
/**
 * Form for Institution Entities 
 *
 * @author afamiglietti
 */

namespace Marca\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AdminBundle\Entity\Coupon'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_adminbundle_institutiontype';
    }
}
