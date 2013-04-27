<?php

namespace Marca\AdminBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Marca\UserBundle\Entity\User;
use Marca\AdminBundle\Entity\Institution;
use FOS\UserBundle\Entity\UserManager;
use Marca\AdminBundle\Form\InstitutionType;

/**
 * Allows Admins to setup and manage information about the educational institutions using an install of Marca
 *
 * @route("/institution", name="institution")
 */
class InstitutionController extends Controller {
   
    /**
     * @Route("/", name="institution")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $institutions = $em->getRepository('MarcaAdminBundle:Institution')->findAll();
        return array('institutions' => $institutions);
    }
    
    /**
     * @Route("/new", name="inst_new_modal")
     * @Template()
     */
    public function newAction()
    {
        $options = array();
        $institution = new Institution();
        $form = $this->createForm(new InstitutionType($options), $institution);
        return array('form' => $form->createView(), 'institution' => $institution);
    }
    
    /**
     * @Route("/create", name="inst_create")
     * @Method("post")
     */
    public function createAction()
    {
        $em = $this->getEm();
        $institution = new Institution();
        $options = array();
        $form = $this->createForm(new InstitutionType($options), $institution);
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()){
            $em->persist($institution);
            $em->flush();
            return $this->redirect($this->generateUrl('institution'));
        }
        return array('form' => $form->createView(), 'institution' => $institution);
    }
}


