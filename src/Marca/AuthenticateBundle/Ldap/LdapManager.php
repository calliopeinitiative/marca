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
use FR3D\LdapBundle\Driver\LdapDriverInterface;

class LdapManager extends BaseLdapManager
{
    private $emailHost;


    public function __construct(LdapDriverInterface $driver, $userManager, array $params, $emailHost)
    {
        $this->emailHost = $emailHost;

        parent::__construct($driver, $userManager, $params);

    }

    protected function hydrate(UserInterface $user, array $entry)
    {
        parent::hydrate($user, $entry);

        $email= $user->getUsername().'@'.$this->emailHost;
        $user->setEmail($email);

    }
}