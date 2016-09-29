<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Marca\CourseBundle\Entity\TermRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;

class PortType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
                ->add('portfolio', 'checkbox', array('label'  => 'Use the Portfolio','attr' => array('class' => 'checkbox inline'),))
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

    public function getName()
    {
        return 'marca_coursebundle_coursetype';
    }
}
