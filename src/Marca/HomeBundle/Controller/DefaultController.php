<?php

namespace Marca\HomeBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Profile controller.
 *
 * @Route("/home")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $pages = $em->getRepository('MarcaHomeBundle:Page')->findAll();

        return array('pages' => $pages);
    }
}
