<?php
/**
 * Created by PhpStorm.
 * User: rlbaltha
 * Date: 8/21/14
 * Time: 1:21 PM
 */

namespace Marca\AuthenticateBundle\Ldap;

use FR3D\LdapBundle\Ldap\LdapManager as BaseLdapManager;
use Symfony\Component\Security\Core\User\UserInterface;

class LdapManager extends BaseLdapManager
{
    protected function hydrate(UserInterface $user, array $entry)
    {
        parent::hydrate($user, $entry);

        $email= $user->getUsername().'@uga.edu';
        $user->setEmail($email);

    }
}