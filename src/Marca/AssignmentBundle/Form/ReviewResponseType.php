<?php

namespace Marca\AssignmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ReviewResponseType extends AbstractType
{
    protected $options;

    public function __construct (array $options)
    {
        $this->options = $options;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
        /* to access the reviewresponse here, (so we can set the id in the query)
        the EventListener pulls the data and the form a part.
        thus, we can get the promptitemid out of the data and drop it in the form builder query.
         */
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $reviewresponse = $event->getData();
            $promptid= $reviewresponse->getReviewPrompt()->getId();

            $form = $event->getForm();
            $form
            ->add('responseShortText','text', array('attr' => array('class' => 'text form-control'), 'label'  => ' ',))
                ->add('responseParagraphText', 'textarea', array('attr' => array('class' => 'text form-control'), 'label'  => ' ',))
                ->add('scaleitem', 'entity', array('class' => 'MarcaAssessmentBundle:Scaleitem','property'=>'name','query_builder' =>
                    function(\Marca\AssessmentBundle\Entity\ScaleitemRepository $sc) use ($promptid) {
                        return $sc->createQueryBuilder('s')
                            ->join("s.scale", 'c')
                            ->join("c.promptitem", 'p')
                            ->where('p.id = :id')
                            ->setParameter('id', $promptid) ;},
                    'expanded'=>true,'required'  => TRUE,'label'  => 'Select','attr' => array('class' => 'radio'),))
                ->add('helpful', 'choice', array('choices'   => array(true => 'Yes', false => 'No'),'required'  => TRUE,'label'  => 'Was this helpful?', 'expanded' => true,'attr' => array('class' => 'radio inline'),));


        });

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Marca\AssignmentBundle\Entity\ReviewResponse'
        ));
    }

    public function getName()
    {
        return 'marca_assignmentbundle_reviewresponsetype';
    }
}
