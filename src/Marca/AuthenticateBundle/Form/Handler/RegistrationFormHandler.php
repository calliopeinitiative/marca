<?php

namespace Marca\AuthenticateBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;
use Marca\DocBundle\Entity\Markupset;

/**
 * Overrides the Registration form handler to add new functionality at user creation time
 */
class RegistrationFormHandler extends BaseHandler 
{
    protected function onSuccess(UserInterface $user, $confirmation) {
        
        $markupset = new Markupset();
        $markupset->setName("Favorites");
        $markupset->setOwner($user);
        $markupset->setDescription("Private Favorites");
        
        parent::onSuccess($user, $confirmation);
  
        
        
    }
}


