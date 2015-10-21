<?php

namespace Marca\HomeBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Home controller.
 *
 */
class DefaultController extends Controller
{
    /**
     * @Route("/home", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $type =0;

        $pages = $em->getRepository('MarcaHomeBundle:Page')->findPageByType($type);

        return array('pages' => $pages);
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        var_dump();die();
    }
}
