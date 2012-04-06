<?php

namespace Marca\DocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\DocBundle\Entity\Markup;
use Marca\DocBundle\Form\MarkupType;

/**
 * Markup controller.
 *
 * @Route("/markup")
 */
class MarkupController extends Controller
{
    /**
     * Lists all Markup entities.
     *
     * @Route("/", name="markup")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MarcaDocBundle:Markup')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Markup entity.
     *
     * @Route("/{id}/show", name="markup_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Markup entity.
     *
     * @Route("/new", name="markup_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Markup();
        $form   = $this->createForm(new MarkupType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Markup entity.
     *
     * @Route("/create", name="markup_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Markup:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        $entity  = new Markup();
        $entity->setUserid($userid);
        $request = $this->getRequest();
        $form    = $this->createForm(new MarkupType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('markup_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Markup entity.
     *
     * @Route("/{id}/edit", name="markup_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $editForm = $this->createForm(new MarkupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Markup entity.
     *
     * @Route("/{id}/update", name="markup_update")
     * @Method("post")
     * @Template("MarcaDocBundle:Markup:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaDocBundle:Markup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Markup entity.');
        }

        $editForm   = $this->createForm(new MarkupType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('markup'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Markup entity.
     *
     * @Route("/{id}/delete", name="markup_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaDocBundle:Markup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Markup entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('markup'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
