<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Marca\CourseBundle\Entity\TermRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;

class CourseType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
            ->add('name','text', array('label'  => 'Name','attr' => array('class' => 'text form-control'),))
            ->add('term','entity', array('class'=>'MarcaCourseBundle:Term', 'query_builder' => function(TermRepository $tr) use ($institutionid){
            $qb = $tr->createQueryBuilder('MarcaCourseBundle:Term');
            $qb->select('t')->from('Marca\CourseBundle\Entity\Term', 't')->innerJoin('t.institution', 'i')->where('t.status != 3')->andWhere('i.id = ?1')->setParameter('1', $institutionid);
            return $qb;
            }
            ,'property'=>'termName','expanded'=>true,'multiple'=>false, 'label' => 'Term','attr' => array('class' => 'radio'),))   
            ->add('parents','entity', array('class'=> 'MarcaCourseBundle:Course', 'query_builder' => function(\Marca\CourseBundle\Entity\CourseRepository $cr) use             ($user){
            $qb = $cr->createQueryBuilder('MarcaCourseBundle:Course');
            $qb->select('c')->from('MarcaCourseBundle:Course', 'c')->where('c.user = ?1 AND c.module = 1')->orWhere('c.module >= 2')->setParameter('1', $user);
            return $qb;
            }
            ,'property'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select associated modules','required' => true,'attr' => array('class' => 'checkbox'),)) 
            ->add('time', 'time', array('widget' => 'single_text','label'=>'Course Time','attr' => array('class' => 'text form-control')))
            ->add('enroll','checkbox', array('label'  => 'Allow students to enroll','attr' => array('class' => 'checkbox'),))
            ->add('post', 'checkbox', array('label'  => 'Allow student to post documents','attr' => array('class' => 'checkbox'),))
            ->add('studentForum', 'checkbox', array('label'  => 'Allow student to start Forum  threads','attr' => array('class' => 'checkbox'),))
            ->add('notes', 'checkbox', array('label'  => 'Use the Notes tool','attr' => array('class' => 'checkbox'),))
            ->add('forum', 'checkbox', array('label'  => 'Use the Forum','attr' => array('class' => 'checkbox'),))
            ->add('journal', 'checkbox', array('label'  => 'Use the Journal','attr' => array('class' => 'checkbox'),))
            ->add('portfolio', 'checkbox', array('label'  => 'Use the Portfolio','attr' => array('class' => 'checkbox inline'),))
            ->add('portset','entity', array('class'=>'MarcaPortfolioBundle:Portset', 'query_builder' => function(PortsetRepository $pr) {
                    $qb = $pr->createQueryBuilder('MarcaPortfolioBundle:Portset');
                    $qb->select('p')->from('MarcaPortfolioBundle:Portset', 'p')->where('p.shared > 0')->andwhere('p.shared < 3');
                    return $qb;
                }
            ,'property'=>'name','expanded'=>true,'multiple'=>false, 'label' => 'Portset','attr' => array('class' => 'radio'),))
            ->add('assessmentset','entity', array('class'=>'MarcaAssessmentBundle:Assessmentset', 'property'=>'name','expanded'=>true,'multiple'=>false, 'label' => 'Select the Assessment Set for the Portfolio','attr' => array('class' => 'radio'),))
            ->add('zine', 'hidden')
            ->add('module', 'hidden')        
            ->add('institution', 'entity', array('class'=>'MarcaAdminBundle:Institution','property'=>'name', 'label'=>'Your Institution','expanded'=>true,'multiple'=>false, 'disabled'=>true,'attr' => array('class' => 'radio'),))
            ->add('portStatus', 'hidden')         
            ->add('tagset','entity', array('class'=>'MarcaTagBundle:Tagset', 'query_builder' => function(TagsetRepository $tr) use ($userid){
            $qb = $tr->createQueryBuilder('MarcaTagBundle:Tagset');
            $qb->select('t')->from('Marca\TagBundle\Entity\Tagset', 't')->innerJoin('t.user', 'u')->where('u.id = ?1')->orWhere('t.shared = 2')->setParameter('1', $userid);
            return $qb;
            }
            ,'property'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select label sets','attr' => array('class' => 'checkbox'),))
            ->add('markupsets','entity', array('class'=>'MarcaDocBundle:Markupset', 'query_builder' => function(MarkupsetRepository $mr) use ($userid){
            $qb = $mr->createQueryBuilder('MarcaDocBundle:Markupset');
            $qb->select('m')->from('Marca\DocBundle\Entity\Markupset', 'm')->innerJoin('m.users', 'u')->where('u.id = ?1')->orWhere('m.shared = 2')->setParameter('1', $userid);
            return $qb;
            }
            ,'property'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select markup sets for Documents','attr' => array('class' => 'checkbox'),));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
