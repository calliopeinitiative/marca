<?php

namespace Marca\PortfolioBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\PortfolioBundle\Entity\Portitem;
use Marca\PortfolioBundle\Form\PortitemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Portitem controller.
 *
 * @Route("/portitem")
 */
class PortitemController extends Controller
{
    /**
     * Lists all Portitem entities.
     *
     * @Route("/", name="portitem")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $portitems = $em->getRepository('MarcaPortfolioBundle:Portitem')->findAll();

        return array('portitems' => $portitems);
    }

    /**
     * Finds and displays a Portitem entity.
     *
     * @Route("/{id}/show", name="portitem_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($id);

        if (!$portitem) {
            throw $this->createNotFoundException('Unable to find Portitem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portitem'      => $portitem,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Portitem entity.
     *
     * @Route("/{portsetid}/new", name="portitem_new")
     * @Template()
     */
    public function newAction($portsetid)
    {
        $portitem = new Portitem();

        $form   = $this->createForm(new PortitemType(), $portitem);

        return array(
            'portsetid' => $portsetid,
            'portitem' => $portitem,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Portitem entity.
     *
     * @Route("/{portsetid}/create", name="portitem_create")
     * @Method("post")
     * @Template("MarcaPortfolioBundle:Portitem:new.html.twig")
     */
    public function createAction($portsetid)
    {
        $em = $this->getEm();
        $portitem  = new Portitem();
        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($portsetid);
        $portitem->setPortset($portset);
        $request = $this->getRequest();
        $form    = $this->createForm(new PortitemType(), $portitem);
        $form->handleRequest($request);


        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($portitem);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $portsetid)));
            
        }

        return array(
            'portitem' => $portitem,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Portitem entity.
     *
     * @Route("/{id}/edit", name="portitem_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($id);

        if (!$portitem) {
            throw $this->createNotFoundException('Unable to find Portitem entity.');
        }

        $editForm = $this->createForm(new PortitemType(), $portitem);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portitem'      => $portitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Portitem entity.
     *
     * @Route("/{id}/update", name="portitem_update")
     * @Method("post")
     * @Template("MarcaPortfolioBundle:Portitem:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($id);
        $portsetid =$portitem->getPortset()->getId();

        if (!$portitem) {
            throw $this->createNotFoundException('Unable to find Portitem entity.');
        }

        $editForm   = $this->createForm(new PortitemType(), $portitem);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($portitem);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $portsetid)));
        }

        return array(
            'portitem'      => $portitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Portitem entity.
     *
     * @Route("/{id}/delete", name="portitem_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($id);
            $portsetid =$portitem->getPortset()->getId();

            if (!$portitem) {
                throw $this->createNotFoundException('Unable to find Portitem entity.');
            }

            $em->remove($portitem);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('portset_show', array('id' => $portsetid)));
    }

    /**
     * Creates a form to delete a Journal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('portset_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
