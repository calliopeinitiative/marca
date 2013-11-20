<?php

namespace Marca\PortfolioBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\PortfolioBundle\Entity\Portset;
use Marca\PortfolioBundle\Entity\Portitem;
use Marca\PortfolioBundle\Form\PortsetType;

/**
 * Portset controller.
 *
 * @Route("/portset")
 */
class PortsetController extends Controller
{
    /**
     * Lists all Portset entities.
     *
     * @Route("/", name="portset")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $portsets = $em->getRepository('MarcaPortfolioBundle:Portset')->findAll();

        return array('portsets' => $portsets);
    }

    /**
     * Finds and displays a Portset entity.
     *
     * @Route("/{id}/show", name="portset_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);
        $portitems = $em->getRepository('MarcaPortfolioBundle:Portitem')->findByPortset($portset);
        if (!$portset) {
            throw $this->createNotFoundException('Unable to find Portset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portset'      => $portset,
            'portitems'    => $portitems,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Portset entity.
     *
     * @Route("/new", name="portset_new")
     * @Template()
     */
    public function newAction()
    {
        $user = $this->getUser();
        $portset = new Portset();
        $portset->setDescription('<p></p>');
        $portset->setUser($user);

        $form   = $this->createForm(new PortsetType(), $portset);

        return array(
            'portset' => $portset,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Portset entity.
     *
     * @Route("/create", name="portset_create")
     * @Method("post")
     * @Template("MarcaPortfolioBundle:Portset:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $portset  = new Portset();
        $portset->setUser($user);
        
        $portitem = new Portitem();
        $portitem->setPortset($portset);
        
        $request = $this->getRequest();
        $form    = $this->createForm(new PortsetType(), $portset);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($portset);
            $em->persist($portitem);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $portset->getId())));
            
        }

        return array(
            'portset' => $portset,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Portset entity.
     *
     * @Route("/{id}/edit", name="portset_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);

        if (!$portset) {
            throw $this->createNotFoundException('Unable to find Portset entity.');
        }

        $editForm = $this->createForm(new PortsetType(), $portset);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portset'      => $portset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Portset entity.
     *
     * @Route("/{id}/update", name="portset_update")
     * @Method("post")
     * @Template("MarcaPortfolioBundle:Portset:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);

        if (!$portset) {
            throw $this->createNotFoundException('Unable to find Portset entity.');
        }

        $editForm   = $this->createForm(new PortsetType(), $portset);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($portset);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $id)));
        }

        return array(
            'portset'      => $portset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Portset entity.
     *
     * @Route("/{id}/delete", name="portset_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);

            if (!$portset) {
                throw $this->createNotFoundException('Unable to find Portset entity.');
            }

            $em->remove($portset);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('portset'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
