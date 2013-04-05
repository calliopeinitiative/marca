<?php

namespace Marca\AdminBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Marca\UserBundle\Entity\User;
use FOS\UserBundle\Entity\UserManager;


/**
 * Enroll controller.
 *
 * @Route("/admin")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $users = $em->getRepository('MarcaUserBundle:User')->findUsersAlphaOrder();
        
        $form = $this->createFormBuilder(new User())
            ->add('lastname','text', array('label'  => 'Start of name, username, or email','attr' => array('class' => 'inline'),))
            ->getForm();
        
        //pagination
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($users,$this->get('request')->query->get('page', 1),25);
        
        return array('user' => $user,'users' => $users, 'form'=>$form->createView());
    }
    
 
    /**
     * Find user by name,email, or username
     * @Route("/find", name="admin_find")
     * @Template("MarcaAdminBundle:Default:index.html.twig")
     * @Method("post")
     */
    public function findAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        
        $request = $this->get('request');
        $postData = $request->request->get('form');
        $name = $postData['lastname'];
        
        $users = $em->getRepository('MarcaUserBundle:User')->findUsersByName($name);
        
        $form = $this->createFormBuilder(new User())
            ->add('lastname','text', array('label'  => 'Start of name, username, or email','attr' => array('class' => 'inline'),))
            ->getForm();
                
        //pagination
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($users,$this->get('request')->query->get('page', 1),25);
        
        return array('user' => $user,'users' => $users, 'form'=>$form->createView());
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
     * Finds Users
     *
     * @Route("/{username}/{role}/promote", name="user_promote")
     * @Template()
     */   
    public function promoteuserAction($username,$role)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $user->addRole($role);
        $userManager->updateUser($user);
        return $this->render('MarcaAdminBundle:Default:show.html.twig', array('user' => $user));
        };
    }  
    
     /**
     * Finds Users
     *
     * @Route("/{username}/{role}/demote", name="user_demote")
     * @Template()
     */   
    public function demoteuserAction($username,$role)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $user->removeRole($role);
        $userManager->updateUser($user);
        return $this->render('MarcaAdminBundle:Default:show.html.twig', array('user' => $user));
        };
    }    
}
