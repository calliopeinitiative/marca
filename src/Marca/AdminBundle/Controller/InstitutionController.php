<?php

namespace Marca\AdminBundle\Controller;

use FOS\UserBundle\Entity\UserManager;
use Marca\AdminBundle\Entity\Institution;
use Marca\AdminBundle\Form\InstitutionType;
use Marca\CourseBundle\Entity\Term;
use Marca\CourseBundle\Form\TermType;
use Marca\HomeBundle\Controller\Controller;
use Marca\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Allows Admins to setup and manage information about the educational institutions using an install of Marca
 *
 * @route("/institution")
 */
class InstitutionController extends Controller {
   
    /**
     * @Route("/", name="institution")
     * @Route("/{id}/index", name="institution2")
     * @Template("MarcaAdminBundle:Institution:index.html.twig")
     */
    public function indexAction($id = null)
    {
        $em = $this->getEm();
        $institutions = $em->getRepository('MarcaAdminBundle:Institution')->findAll();
        $deleteForm = $this->createDeleteForm(0);
        return array('institutions' => $institutions, 'newInstitution' => $id,'delete_form' => $deleteForm->createView());
    }
    
    /**
     * @Route("/new", name="inst_new_modal")
     * @Template("MarcaAdminBundle:Institution:new.html.twig")
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
     */
    public function createAction()
    {
        $em = $this->getEm();
        $institution = new Institution();
        $options = array();
        $form = $this->createForm(new InstitutionType($options), $institution);
        $request = $this->getRequest();
        $form->submit($request);
        if ($form->isValid()){
            $em->persist($institution);
            $em->flush();
            return $this->redirect($this->generateUrl('institution2', array('id' => $institution->getId())));
        }
        return array('form' => $form->createView(), 'institution' => $institution);
    }

 /**
     * Deletes an Institution entity.
     *
     * @Route("/delete/{id}", name="institution_delete")
     */
    public function deleteAction($id)
    {
        
        $em = $this->getEm();
        $institution = $em->getRepository('MarcaAdminBundle:Institution')->find($id);
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();
        
        $form->submit($request);

        if ($form->isValid()) {
            

            if (!$institution) {
                throw $this->createNotFoundException('Unable to find Institution entity.');
            }

            $em->remove($institution);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('institution'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }

    
}

