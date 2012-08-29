<?php

namespace Marca\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UploadType extends AbstractType
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
             ->add('name','hidden')
             ->add('project', 'entity', array('class' => 'MarcaCourseBundle:Project','property'=>'name','query_builder' => 
                function(\Marca\CourseBundle\Entity\ProjectRepository $er) use ($options) {
                $courseid = $options['courseid'] ;
                return $er->createQueryBuilder('p')
                ->where('p.course = :course')
                ->setParameter('course', $courseid)        
                ->orderBy('p.name', 'ASC');},))    
             ->add('file')
            ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\FileBundle\Entity\Upload'
        ));
    }

    public function getName()
    {
        return 'marca_filebundle_filetype';
    }
}
