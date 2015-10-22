<?php

namespace Marca\AuthenticateBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;
use BeSimple\SsoAuthBundle\Security\Core\User\UserFactoryInterface as UserFactoryInterface;


class RegistrationController extends BaseController implements UserFactoryInterface
{
     /**
      * Create User
      *
      * @return User
      */
     public function createUser($username, array $roles, array $attributes)
     {
          $email = $username . '@uga.edu';
          $userManager = $this->container->get('fos_user.user_manager');
          $user = $userManager->createUser();
          $user->setUsername($username);
          $user->setEmail($email);
          $userManager->updateUser($user);

          return $user;
     }

}
