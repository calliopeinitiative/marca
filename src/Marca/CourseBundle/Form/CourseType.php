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
            ->add('time')
            ->add('userid', 'hidden')
            ->add('enroll')
            ->add('post')
            ->add('portRubricId', 'hidden')
            ->add('multicult')
            ->add('projectDefaultId', 'hidden')
            ->add('parentId', 'hidden')
            ->add('assessmentId', 'hidden')
            ->add('studentForum')
            ->add('notes')
            ->add('forum')  
            ->add('journal')
            ->add('portfolio')
            ->add('zine')
            ->add('portStatus', 'hidden')
        ;
    }

    public function getName()
    {
        return 'marca_coursebundle_coursetype';
    }
}
