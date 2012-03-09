<?php

namespace Marca\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\UserBundle\Entity\Profile;
use Marca\UserBundle\Form\ProfileType;

/**
 * Profile controller.
 *
 * @Route("/enroll")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
    }
    
    /**
     * @Route("/find", name="enroll_find")
     * @Template()
     */
    public function findCourseAction()
    {
        $entity = new Profile();
        $form = $this->createFormBuilder($entity)
            ->add('lastname')
            ->getForm();

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }    
}
