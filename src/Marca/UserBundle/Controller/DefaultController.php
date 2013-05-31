<?php

namespace Marca\UserBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\UserBundle\Entity\User;
use Marca\UserBundle\Form\UserType;
use Marca\UserBundle\Form\NewuserType;

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
        $id = $user->getId();
        if ($user->getLastname()==''){
            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }
        $username = $user->getFirstname().' '.$user->getLastname();
        $session = $this->get('session'); 
        $session->set('username', $username); 
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($user);
        $pending = $em->getRepository('MarcaCourseBundle:Course')->findPendingCourses($user);
        
        
        return array('user' => $user,'courses' => $courses, 'pending' => $pending,);
    }
    
    /**
     * @Route("/archive", name="user_archive")
     * @Template("MarcaUserBundle:Default:index.html.twig")
     */
    public function archiveAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $id = $user->getId();
        if ($user->getLastname()==''){
            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }
        $username = $user->getFirstname().' '.$user->getLastname();
        $session = $this->get('session'); 
        $session->set('username', $username); 
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findArchivedCourses($user);
        $pending = '';       
        
        return array('user' => $user,'courses' => $courses, 'pending' => $pending,);
    }    
    
    /**
     * @Route("/course_select_modal", name="course_select_modal")
     * @Template("MarcaUserBundle:Default:course_select_modal.html.twig")
     */
    public function courseSelectAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $username = $user->getFirstname().' '.$user->getLastname();
        $session = $this->get('session'); 
        $session->set('username', $username); 
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($user);
        
        return array('user' => $user,'courses' => $courses);
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
        if (strlen($user->getLastname())==0 or strlen($user->getFirstname())==0) {
            $editForm = $this->createForm(new NewuserType(), $user);
        }
        else {
            $editForm = $this->createForm(new UserType(), $user);
        }

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
        
        $institution = $user->getInstitution();
        
        if (!$institution) {
            $institution = $em->getRepository('MarcaAdminBundle:Institution')->findDefault();
            $user->setInstitution($institution);
        }        

        $editForm   = $this->createForm(new UserType(), $user);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show'));
        }

        return array(
            'user'      => $user,
            'edit_form'   => $editForm->createView(),
        );
    }
    
}
