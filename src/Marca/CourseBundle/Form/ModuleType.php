<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Marca\CourseBundle\Entity\TermRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;

class ModuleType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
            ->add('name','text', array('attr' => array('class' => 'text form-control'),))
            ->add('tagset','entity', array('class'=>'MarcaTagBundle:Tagset', 'query_builder' => function(TagsetRepository $tr) use ($userid){
                $qb = $tr->createQueryBuilder('MarcaTagBundle:Tagset');
                $qb->select('t')->from('Marca\TagBundle\Entity\Tagset', 't')->innerJoin('t.user', 'u')->where('u.id = ?1')->orWhere('t.shared = 2')->setParameter('1', $userid);
                return $qb;
            }
            ,'choice_label'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select label sets','attr' => array('class' => 'checkbox'),))
        ;}
    
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
