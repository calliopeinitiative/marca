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
            ->add('term')
            ->add('time')
            ->add('userid')
            ->add('enroll')
            ->add('post')
            ->add('portRubricId')
            ->add('multicult')
            ->add('projectDefaultId')
            ->add('parentId')
            ->add('assessmentId')
            ->add('studentForum')
            ->add('notes')
            ->add('journal')
            ->add('portfolio')
            ->add('zine')
            ->add('announcement')
            ->add('portStatus')
            ->add('created')
            ->add('updated')
        ;
    }

    public function getName()
    {
        return 'marca_coursebundle_coursetype';
    }
}
