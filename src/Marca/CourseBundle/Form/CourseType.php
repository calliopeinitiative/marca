<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CourseType extends AbstractType
{
     
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('term','entity', array('class'=>'MarcaCourseBundle:Term', 'property'=>'termName', ))
            ->add('time', 'time', array('widget' => 'single_text')) 
            ->add('enroll')
            ->add('post')
            ->add('portRubricId', 'hidden')
            ->add('multicult') 
            ->add('parentId', 'hidden')
            ->add('assessmentId', 'hidden')
            ->add('studentForum')
            ->add('notes')
            ->add('forum')  
            ->add('journal')
            ->add('portfolio')
            ->add('zine')
            ->add('portStatus', 'hidden')
            ->add('tagset','entity', array('class'=>'MarcaTagBundle:Tagset', 'property'=>'name','expanded'=>true,'multiple'=>true, ))     
        ;
    }

    public function getName()
    {
        return 'marca_coursebundle_coursetype';
    }
}
