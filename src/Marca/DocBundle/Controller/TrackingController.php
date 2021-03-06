<?php

namespace Marca\DocBundle\Controller;


use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Marca\DocBundle\Entity\Tracking;

/**
 * Tracking controller.
 *
 * @Route("/tracking")
 */
class TrackingController extends Controller
{

    /**
     * Lists all Tracking entities.
     *
     * @Route("/", name="tracking")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $tracking = $em->getRepository('MarcaDocBundle:Tracking')->countMarkup();

        return array(
            'tracking' => $tracking,
        );
    }

    /**
     * Lists all Tracking entities.
     *
     * @Route("/{fileid}/add", name="tracking_add")
     * @Method("POST")
     */
    public function addAction($fileid)
    {
        $em = $this->getEm();
        $request = $this->getRequest();
        $markupid = $request->request->get('markupid');
        $file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $markup = $em->getRepository('MarcaDocBundle:Markup')->find($markupid);
        $tracking = new Tracking();
        $tracking->setFile($file);
        $tracking->setMarkup($markup);
        $em->persist($tracking);
        $em->flush();

        $return = "success";
        return new Response($return,200,array('Content-Type'=>'application/json'));
    }    

    /**
     * Finds and displays a Tracking entity.
     *
     * @Route("/{id}", name="tracking_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $tracking_item = $em->getRepository('MarcaDocBundle:Tracking')->find($id);

        if (!$tracking_item) {
            throw $this->createNotFoundException('Unable to find Tracking entity.');
        }

        return array(
            'tracking_item'      => $tracking_item,
        );
    }
}
