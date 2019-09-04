<?php

namespace Marca\HomeBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
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
        $type =0;

        $pages = $em->getRepository('MarcaHomeBundle:Page')->findPageByType($type);

        return array('pages' => $pages);
    }
}
