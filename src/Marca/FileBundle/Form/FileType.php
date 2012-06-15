<?php

namespace Marca\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class FileType extends AbstractType
{
    protected $options;

     public function __construct (array $options)
     {
        $this->options = $options;
     }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $options = $this->options;
        $builder
             ->add('name')
             ->add('project', 'entity', array('class' => 'MarcaCourseBundle:Project','property'=>'name','query_builder' => 
                function(\Marca\CourseBundle\Entity\ProjectRepository $er) use ($options) {
                $courseid = $options['courseid'] ;
                return $er->createQueryBuilder('p')
                ->where('p.course = :course')
                ->setParameter('course', $courseid)        
                ->orderBy('p.name', 'ASC');}, 'expanded'=>true,)) 
                ->add('tag', 'entity', array('class' => 'MarcaTagBundle:Tag','property'=>'name','query_builder' => 
                  function(\Marca\TagBundle\Entity\TagRepository $er) use ($options) {
                  $courseid = $options['courseid'] ;  
                  return $er->createQueryBuilder('t')
                        ->join("t.tagset", 's')
                        ->join("s.course", 'c')
                        ->where('c.id = :course')
                        ->setParameter('course', $courseid)        
                        ->orderBy('c.name', 'ASC');
                }, 'expanded'=>true,'multiple'=>true,
              ))  
             ->add('access', 'choice', array('choices'   => array('0' => 'Private', '1' => 'Shared'),'required'  => true, 'expanded'=>true,'multiple'=>false,))           
            ;
    }
                 
                        
    public function getName()
    {
        return 'marca_filebundle_filetype';
    }
}