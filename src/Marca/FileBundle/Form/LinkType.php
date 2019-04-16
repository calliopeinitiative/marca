<?php

namespace Marca\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    protected $options;

     public function __construct (array $options)
     {
        $this->options = $options;
     }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->options;
        $builder
             ->add('name',TextType::class, array('attr' => array('class' => 'text form-control', 'placeholder' => 'Name of your link'),))
             ->add('url',TextType::class, array('attr' => array('class' => 'text form-control', 'placeholder' => 'http://yourlink.edu'),))
             ->add('project', 'entity', array('class' => 'MarcaCourseBundle:Project','property'=>'name','query_builder' => 
                function(\Marca\CourseBundle\Entity\ProjectRepository $er) use ($options) {
                $courseid = $options['courseid'] ;
                $resource = $options['resource'] ;
                return $er->createQueryBuilder('p')
                ->where('p.course = :course')
                ->andWhere('p.resource = :resource')        
                ->setParameter('course', $courseid)  
                ->setParameter('resource', $resource)       
                ->orderBy('p.name', 'ASC');}, 'expanded'=>true,'multiple'=>false,'label'  => 'Folder', 'attr' => array('class' => 'radio'),))
              ->add('tag', 'entity', array('class' => 'MarcaTagBundle:Tag','property'=>'name','query_builder' => 
                  function(\Marca\TagBundle\Entity\TagRepository $er) use ($options) {
                  $courseid = $options['courseid'] ;  
                  return $er->createQueryBuilder('t')
                        ->join("t.tagset", 's')
                        ->join("s.course", 'c')
                        ->where('c.id = :course')
                        ->andWhere('t.id!=3') 
                        ->andWhere('t.id!=5')  
                        ->setParameter('course', $courseid)        
                        ->orderBy('c.name', 'ASC');
                }, 'expanded'=>true,'multiple'=>true, 'label'  => 'Labels', 'attr' => array('class' => 'checkbox'),
              ))  
             ->add('access', ChoiceType::class, array('choices'   => array('0' => 'Private', '1' => 'Shared'),'multiple'=>false,'label'  => 'Sharing', 'expanded' => true,'attr' => array('class' => 'radio'),))
            ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\FileBundle\Entity\File'
        ));
    }
                 
                        
    public function getBlockPrefix()
    {
        return 'marca_filebundle_filetype';
    }
}
