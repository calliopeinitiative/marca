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
                ->orderBy('p.name', 'ASC');},))    
             ->add('userid', 'hidden')  
             ->add('courseid', 'hidden')
             ->add('file')
            ;
    }

    public function getName()
    {
        return 'marca_filebundle_filetype';
    }
}
