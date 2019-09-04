<?php

namespace Marca\AdminBundle\Controller;

use Marca\AdminBundle\Form\AdminUserType;
use Marca\HomeBundle\Controller\Controller;
use Marca\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Enroll controller.
 *
 * @Route("/admin")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $users = array();
        
        $form = $this->createFormBuilder(new User())
            ->add('lastname','text', array('label'  => 'Start of name, username, or email','attr' => array('class' => 'form-control', 'action' => 'javascript:void(0);'),))
            ->getForm();

        //pagination
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($users,$request->query->get('page', 1),25);
        $count = $users->getTotalItemCount();
        return $this->render('MarcaAdminBundle:Default:index.html.twig', array(
            'count' => $count,
            'user' => $user,
            'users' => $users,
            'form'=>$form->createView()
        ));
    }
    
 
    /**
     * Find user by name,email, or username
     * @Route("/find", name="admin_find")
     * @Method("POST")
     */
    public function findAction(Request $request)
    {
        $em = $this->getEm();
        $user = $this->getUser();

        $postData = $request->request->get('form');
        $name = $postData['lastname'];
        $users = $em->getRepository('MarcaUserBundle:User')->findUsersByName($name);

        //pagination
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($users,$request->query->get('page', 1),25);
        $count = $users->getTotalItemCount();

        return $this->render('MarcaAdminBundle:Default:find.html.twig',  array(
            'count' => $count,
            'user' => $user,
            'users' => $users,
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{username}/edit", name="admin_user_edit")
     * @Template("MarcaAdminBundle:Default:edit.html.twig")
     */
    public function editAction($username)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
            $editForm = $this->createEditForm($user);

        return array(
            'user'      => $user,
            'edit_form'   => $editForm->createView(),
        );
    }


    /**
     * Creates a form to edit a Grade entity.
     *
     * @param User $user The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $user)
    {
        $form = $this->createForm(new AdminUserType(), $user, array(
            'action' => $this->generateUrl('admin_user_update', array('username' => $user->getUsername())),
            'method' => 'POST',
            'attr' => array('novalidate' => 'novalidate'),
        ));

        $form->add('submit', 'submit', array('label' => 'Update','attr' => array('class' => 'btn btn-default'),));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{username}/update", name="admin_user_update")
     * @Method("post")
     * @Template("MarcaAdminBundle:Default:edit.html.twig")
     */
    public function updateAction(Request $request, $username)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }



        $editForm = $this->createEditForm($user);

        $postData = $request->get('marca_adminbundle_useradmin');
        $new_username = $postData['username'];
        $new_email = $postData['email'];

        $username_requested = $userManager->findUserByUsername($new_username);
        $email_requested = $userManager->findUserByEmail($new_email);

        if ($new_username!=$username && ($username_requested || $email_requested)) {
            $this->get('session')->getFlashBag()->add('error', 'That username or email is already in use.  Please try another.');
            return $this->redirect($this->generateUrl('user_admin', array('username' => $username)));
        }

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $userManager->updateUser($user);

            return $this->redirect($this->generateUrl('user_admin', array('username' => $user->getUsername())));
        }

        return array(
            'user'      => $user,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Finds Users
     *
     * @Route("/{username}/admin", name="user_admin")
     * @Template()
     */
    public function adminAction($username)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        return $this->render('MarcaAdminBundle:Default:show.html.twig', array('user' => $user));
    }

    /**
     * Promote Users
     *
     * @Route("/{username}/{role}/promote", name="user_promote")
     * @Template()
     */   
    public function promoteuserAction($username,$role)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $user->addRole($role);
        $userManager->updateUser($user);
        return $this->render('MarcaAdminBundle:Default:show.html.twig', array('user' => $user));
    }  
    
     /**
     * Demote Users
     *
     * @Route("/{username}/{role}/demote", name="user_demote")
     * @Template()
     */   
    public function demoteuserAction($username,$role)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $user->removeRole($role);
        $userManager->updateUser($user);
        return $this->render('MarcaAdminBundle:Default:show.html.twig', array('user' => $user));
    }    
}
