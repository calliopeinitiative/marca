<?php

namespace Marca\AssignmentBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class AssignmentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('description');

        $builder->add('resources', 'entity', array(
            'class' => 'MarcaFileBundle:File',
            'property' => 'name',
            'multiple' => 'true',
            'required' => 'false',
            'query_builder' => function(EntityRepository $er) use($options) {
                return $er->createQueryBuilder('f')->join('f.project','p')->where('f.course=?1')->andWhere('p.resource=true')->setParameters(array('1'=>$options['course']));
            }
        ));

        $builder->add('stages', 'collection', array('type' => new AssignmentStageType(), 'allow_add' => true, 'by_reference' => false,));

        $builder->add('save', 'submit', array('label' => 'Create New Assignment'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Marca\AssignmentBundle\Entity\Assignment', 'course'=>null));
    }

    public function getName()
    {
        return 'assignment';
    }

} 