<?php

namespace Marca\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PortfolioType extends AbstractType
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
            ->add('portitem', 'entity', array('class' => 'MarcaPortfolioBundle:Portitem','property'=>'name','query_builder' => 
            function(\Marca\PortfolioBundle\Entity\PortitemRepository $er) use ($options) {
            $courseid = $options['courseid'] ;  
            return $er->createQueryBuilder('p')
                  ->join("p.portset", 'o')
                  ->join("o.course", 'c')
                  ->where('c.id = :courseid')
                  ->setParameter('courseid', $courseid)        
                  ->orderBy('p.name', 'ASC');
                }, 'expanded'=>false,'multiple'=>false, 'label'  => 'Select a portfolio category',
              ))     
            ->add('portorder')        
        ;
    }

    public function getName()
    {
        return 'marca_portfoliobundle_portfoliotype';
    }
}
