<?php

namespace Marca\PortfolioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Marca\HomeBundle\Controller\Controller;
use Marca\PortfolioBundle\Entity\Portitem;
use Marca\PortfolioBundle\Form\PortitemType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $portitems = $em->getRepository('MarcaPortfolioBundle:Portitem')->findAll();


        return $this->render('MarcaPortfolioBundle:Portitem:index.html.twig', array(
            'portitems' => $portitems
        ));
    }

    /**
     * Finds and displays a Portitem entity.
     *
     * @Route("/{id}/show", name="portitem_show")
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($id);

        if (!$portitem) {
            throw $this->createNotFoundException('Unable to find Portitem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaPortfolioBundle:Portitem:show.html.twig', array(
            'portitem'      => $portitem,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Portitem entity.
     *
     * @Route("/{portsetid}/new", name="portitem_new")
     */
    public function newAction($portsetid)
    {
        $portitem = new Portitem();

        $form   = $this->createForm(PortitemType::class, $portitem);

        return $this->render('MarcaPortfolioBundle:Portitem:new.html.twig', array(
            'portsetid' => $portsetid,
            'portitem' => $portitem,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Portitem entity.
     *
     * @Route("/{portsetid}/create", name="portitem_create", methods={"POST"})
     */
    public function createAction(Request $request, $portsetid)
    {
        $em = $this->getEm();
        $portitem  = new Portitem();
        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($portsetid);
        $portitem->setPortset($portset);
        $form    = $this->createForm(PortitemType::class, $portitem);
        $form->handleRequest($request);


        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($portitem);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $portsetid)));
            
        }

        return $this->render('MarcaPortfolioBundle:Portitem:new.html.twig', array(
            'portitem' => $portitem,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Portitem entity.
     *
     * @Route("/{id}/edit", name="portitem_edit")
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($id);

        if (!$portitem) {
            throw $this->createNotFoundException('Unable to find Portitem entity.');
        }

        $editForm = $this->createForm(PortitemType::class, $portitem);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaPortfolioBundle:Portitem:edit.html.twig', array(
            'portitem'      => $portitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Portitem entity.
     *
     * @Route("/{id}/update", name="portitem_update", methods={"POST"})
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getEm();

        $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($id);
        $portsetid =$portitem->getPortset()->getId();

        if (!$portitem) {
            throw $this->createNotFoundException('Unable to find Portitem entity.');
        }

        $editForm   = $this->createForm(PortitemType::class, $portitem);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($portitem);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $portsetid)));
        }

        return $this->render('MarcaPortfolioBundle:Portitem:edit.html.twig', array(
            'portitem'      => $portitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Portitem entity.
     *
     * @Route("/{id}/delete", name="portitem_delete", methods={"POST"})
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

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
            ->add('submit', SubmitType::class, array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
