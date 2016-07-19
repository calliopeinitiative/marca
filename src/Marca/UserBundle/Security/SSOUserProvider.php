<?php

namespace Marca\UserBundle\Security;

use Marca\UserBundle\Entity\User;
use BeSimple\SsoAuthBundle\Security\Core\User\UserFactoryInterface;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Security\UserProvider;
use FOS\UserBundle\Util\TokenGenerator;

class SSOUserProvider extends UserProvider implements UserFactoryInterface
{
    private $em;
    private $tokenGenerator;

    public function __construct(UserManagerInterface $userManager, EntityManager $em, TokenGenerator $tokenGenerator)
    {
        parent::__construct($userManager);
        $this->em = $em;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function createUser($username, array $roles, array $attributes)
    {
        $email = $username.'@uga.edu';
        $password = substr($this->tokenGenerator->generateToken(), 0, 12);
        $user = new User();
        $user->setUsername($username);
        $user->setUsernameCanonical($username);
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setEnabled(true);
        $user->setPlainPassword($password);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}