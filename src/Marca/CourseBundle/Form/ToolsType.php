<?php

namespace Marca\CourseBundle\Form;

use Marca\CourseBundle\Entity\TermRepository;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToolsType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
            ->add('notes', CheckboxType::class, array('label'  => 'Use the Notes tool','attr' => array('class' => 'checkbox'),))
            ->add('forum', CheckboxType::class, array('label'  => 'Use the Forum','attr' => array('class' => 'checkbox'),))
            ->add('journal', CheckboxType::class, array('label'  => 'Use the Journal','attr' => array('class' => 'checkbox'),))
            ->add('portfolio', CheckboxType::class, array('label'  => 'Use the Portfolio','attr' => array('class' => 'checkbox inline'),))
            ->add('portset','entity', array('class'=>'MarcaPortfolioBundle:Portset', 'query_builder' => function(PortsetRepository $pr) {
                    $qb = $pr->createQueryBuilder('MarcaPortfolioBundle:Portset');
                    $qb->select('p')->from('MarcaPortfolioBundle:Portset', 'p')->where('p.shared > 0')->andwhere('p.shared < 3');
                    return $qb;
                }
            ,'choice_label'=>'name','expanded'=>true,'multiple'=>false, 'label' => 'Portset','attr' => array('class' => 'radio'),));
            
}
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Course'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_coursebundle_coursetype';
    }
}
