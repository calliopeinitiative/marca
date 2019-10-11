<?php

namespace Marca\CourseBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $institutionid = $user->getInstitution()->getId();
        $builder
            ->add('name',TextType::class, array('attr' => array('class' => 'text form-control'),))
            ->add('term', EntityType::class, array('class' => 'MarcaCourseBundle:Term','query_builder' =>
                function(\Marca\CourseBundle\Entity\TermRepository $er) use ($institutionid) {
                    return $er->createQueryBuilder('t')
                        ->innerJoin('t.institution', 'i')
                        ->where('t.status != 3')
                        ->andWhere('i.id = ?1')
                        ->setParameter('1', $institutionid);},
                'choice_label' => 'termName','expanded'=>true,'multiple'=>false,'label'  => 'Folder', 'attr' => array('class' => 'radio'),))
            ->add('parents', EntityType::class, array('class' => 'MarcaCourseBundle:Course','query_builder' =>
                function(\Marca\CourseBundle\Entity\CourseRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = ?1 AND c.module = 1')
                        ->orWhere('c.module >= 2')
                        ->setParameter('1', $user);},
                'choice_label'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select associated modules','required' => true,'attr' => array('class' => 'checkbox'),))
            ->add('time', TimeType::class, array('widget' => 'single_text','label'=>'Course Time','attr' => array('class' => 'text form-control')))
            ->add('enroll',CheckboxType::class, array('label'  => 'Allow students to enroll','label_attr' => array('class' => 'checkbox'),))
            ->add('post', CheckboxType::class, array('label'  => 'Allow student to post documents','label_attr' => array('class' => 'checkbox'),))
            ->add('studentForum', CheckboxType::class, array('label'  => 'Allow student to start Forum  threads','label_attr' => array('class' => 'checkbox'),))
            ->add('notes', CheckboxType::class, array('label'  => 'Use the Notes tool','label_attr' => array('class' => 'checkbox'),))
            ->add('forum', CheckboxType::class, array('label'  => 'Use the Forum','label_attr' => array('class' => 'checkbox'),))
            ->add('journal', CheckboxType::class, array('label'  => 'Use the Journal','label_attr' => array('class' => 'checkbox'),))
            ->add('portfolio', CheckboxType::class, array('label'  => 'Use the Portfolio','label_attr' => array('class' => 'checkbox inline'),))
            ->add('portset', EntityType::class, array('class' => 'MarcaPortfolioBundle:Portset','query_builder' =>
                function(\Marca\PortfolioBundle\Entity\PortsetRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.shared > 0')
                        ->andwhere('p.shared < 3');},
                'choice_label'=>'name','expanded'=>true,'multiple'=>false, 'label' => 'Portset','attr' => array('class' => 'radio'),))
            ->add('tagset', EntityType::class, array('class' => 'MarcaTagBundle:Tagset','query_builder' =>
                function(\Marca\TagBundle\Entity\TagsetRepository $er) use ($userid) {
                    return $er->createQueryBuilder('t')
                        ->innerJoin('t.user', 'u')
                        ->where('u.id = ?1')
                        ->orWhere('t.shared = 2')
                        ->setParameter('1', $userid);},
                    'choice_label'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select label sets','attr' => array('class' => 'checkbox'),))
            ->add('markupsets', EntityType::class, array('class' => 'MarcaDocBundle:Markupset','query_builder' =>
                function(\Marca\DocBundle\Entity\MarkupsetRepository $er) use ($userid) {
                    return $er->createQueryBuilder('m')
                        ->innerJoin('m.users', 'u')
                        ->where('u.id = ?1')
                        ->orWhere('m.shared = 2')
                        ->orWhere('m.shared = 1')
                        ->setParameter('1', $userid);},
                    'choice_label'=>'name','expanded'=>true,'multiple'=>true, 'label' => 'Select markup sets for Documents','attr' => array('class' => 'checkbox'),));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\CourseBundle\Entity\Course'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_coursebundle_coursetype';
    }
}
