<?php

namespace Marca\UserBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\UserBundle\Entity\User;
use Marca\UserBundle\Form\UserType;

/**
 * Enroll controller.
 *
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/home", name="user_home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($user);
        $pending = $em->getRepository('MarcaCourseBundle:Course')->findPendingCourses($user);
        
        $userfind = new User();
        $form = $this->createFormBuilder($userfind)
            ->add('lastname')
            ->getForm();
        
        return array('user' => $user,'courses' => $courses, 'pending' => $pending, 'form'   => $form->createView());
    }
    
    /**
     * Finds and displays a User profile.
     *
     * @Route("/show", name="user_show")
     * @Template()
     */
    public function showAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }
        return array('user' => $user);
    } 
    
    
    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $user);

        return array(
            'user'      => $user,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/update", name="user_update")
     * @Method("post")
     * @Template("MarcaUserBundle:Default:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm   = $this->createForm(new UserType(), $user);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $id)));
        }

        return array(
            'user'      => $user,
            'edit_form'   => $editForm->createView(),
        );
    }
    
}
