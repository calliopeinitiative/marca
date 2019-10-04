<?php

namespace Marca\FileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Marca\CourseBundle\Entity\ProjectRepository;
use Marca\TagBundle\Entity\TagRepository;

class LinkType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options['options'];
        $builder
             ->add('name',TextType::class, array('attr' => array('class' => 'text form-control', 'placeholder' => 'Name of your link'),))
             ->add('url',TextType::class, array('attr' => array('class' => 'text form-control', 'placeholder' => 'http://yourlink.edu'),))
             ->add('project', EntityType::class, array('class' => 'MarcaCourseBundle:Project','query_builder' =>
                function(ProjectRepository $er) use ($options) {
                $courseid = $this->options['courseid'] ;
                $resource = $this->options['resource'] ;
                return $er->createQueryBuilder('p')
                ->where('p.course = :course')
                ->andWhere('p.resource = :resource')        
                ->setParameter('course', $courseid)  
                ->setParameter('resource', $resource)       
                ->orderBy('p.name', 'ASC');},
                 'choice_label' => 'name','expanded'=>true,'multiple'=>false,'label'  => 'Folder',
                 'attr' => array('class' => 'radio')))
            ->add('tag', EntityType::class, array('class' => 'MarcaTagBundle:Tag','query_builder' =>
                  function(TagRepository $er) use ($options) {
                  $courseid = $this->options['courseid'] ;
                  return $er->createQueryBuilder('t')
                        ->join("t.tagset", 's')
                        ->join("s.course", 'c')
                        ->where('c.id = :course')
                        ->andWhere('t.id!=3') 
                        ->andWhere('t.id!=5')  
                        ->setParameter('course', $courseid)        
                        ->orderBy('c.name', 'ASC');
                },'choice_label' => 'name', 'expanded'=>true,'multiple'=>true, 'label'  => 'Labels', 'attr' => array('class' => 'checkbox'),
              ))
            ->add('access', ChoiceType::class, array('choices'   => array('Private' => '0', 'Shared' => '1'),'required'  => true, 'expanded'=>true,'multiple'=>false,'label'  => 'Sharing','attr' => array('class' => 'radio'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\FileBundle\Entity\File',
            'options' => null,
        ));
    }
                 
                        
    public function getBlockPrefix()
    {
        return 'marca_filebundle_filetype';
    }
}
