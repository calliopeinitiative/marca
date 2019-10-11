<?php

namespace Marca\CourseBundle\Form;

use Marca\CourseBundle\Entity\TermRepository;
use Marca\DocBundle\Entity\MarkupsetRepository;
use Marca\PortfolioBundle\Entity\PortsetRepository;
use Marca\TagBundle\Entity\TagsetRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherType extends AbstractType
{
     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];
        $user = $course->getUser();
        $userid = $course->getUser()->getId();
        $builder
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
