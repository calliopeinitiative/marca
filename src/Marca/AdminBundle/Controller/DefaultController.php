<?php

namespace Marca\AdminBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        //pagination
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($users,$this->get('request')->query->get('page', 1),25);
        
        return array('user' => $user,'users' => $users);
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
