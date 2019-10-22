<?php

namespace Marca\AssessmentBundle\Form;

use Marca\AssessmentBundle\Entity\ScaleitemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options['options'];
        $builder
            ->add('scaleitem', EntityType::class, array('class' => 'MarcaAssessmentBundle:Scaleitem','query_builder' =>
                function(ScaleitemRepository $er) use ($options) {
                    $scale = $this->options['scale'] ;
                    return $er->createQueryBuilder('s')
                        ->where('s.scale = ?1')
                        ->setParameter('1', $scale);},
                'choice_label' => 'name','expanded'=>true,'multiple'=>false,'label'  => 'Folder', 'attr' => array('class' => 'radio'),));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssessmentBundle\Entity\Rating',
            'options' => null,
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_assessmentbundle_ratingtype';
    }
}
