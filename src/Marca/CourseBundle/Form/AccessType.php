<?php

namespace Marca\CourseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Marca\CourseBundle\Entity\TermRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;

class AccessType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
            ->add('enroll','checkbox', array('label'  => 'Allow students to enroll','attr' => array('class' => 'checkbox'),))
            ->add('post', 'checkbox', array('label'  => 'Allow student to post documents','attr' => array('class' => 'checkbox'),))
            ->add('studentForum', 'checkbox', array('label'  => 'Allow student to start Forum  threads','attr' => array('class' => 'checkbox'),));
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
