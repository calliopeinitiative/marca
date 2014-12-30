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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;




class RegistrationController extends BaseController
{
    /**
    * Tell the user to check his email provider
    * @Route("/newcheckemail", name="new_check_email")
    **/
    public function newCheckEmailAction(Request $request)
    {
        $email = $request->query->get('email');
        $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('security.context')->getToken()->getUser();
        if (null === $user) {
        throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }
        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:checkEmail.html.'.$this->getEngine(), array(
        'user' => $user,
        ));
}

}
