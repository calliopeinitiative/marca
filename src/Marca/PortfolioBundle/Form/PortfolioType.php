<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioType extends AbstractType
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
            ->add('portitem', 'entity', array('class' => 'MarcaPortfolioBundle:Portitem','property'=>'name','query_builder' => 
            function(\Marca\PortfolioBundle\Entity\PortitemRepository $er) use ($options) {
            $courseid = $options['courseid'] ;  
            return $er->createQueryBuilder('p')
                  ->join("p.portset", 'o')
                  ->join("o.course", 'c')
                  ->where('c.id = :courseid')
                  ->setParameter('courseid', $courseid)        
                  ->orderBy('p.name', 'ASC');
                }, 'expanded'=>false,'multiple'=>false, 'label'  => 'Select a category ','attr' => array('class' => 'form-control'),
              ))     
            ->add('portorder',null,array('label' => 'Select a position ','attr' => array('class' => 'text form-control'),))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\PortfolioBundle\Entity\Portfolio'
        ));
    }

    public function getBlockPrefix()
    {
        return 'marca_portfoliobundle_portfoliotype';
    }
}
