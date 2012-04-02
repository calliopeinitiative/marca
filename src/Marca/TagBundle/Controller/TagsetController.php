<?php

namespace Marca\TagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\TagBundle\Entity\Tagset;
use Marca\TagBundle\Form\TagsetType;

/**
 * Tagset controller.
 *
 * @Route("/tagset")
 */
class TagsetController extends Controller
{
    /**
     * Lists all Tagset entities.
     *
     * @Route("/", name="tagset")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MarcaTagBundle:Tagset')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Tagset entity.
     *
     * @Route("/{id}/show", name="tagset_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tagset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tagset entity.
     *
     * @Route("/new", name="tagset_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tagset();
        $form   = $this->createForm(new TagsetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tagset entity.
     *
     * @Route("/create", name="tagset_create")
     * @Method("post")
     * @Template("MarcaTagBundle:Tagset:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId();    
        $entity  = new Tagset();
        $entity->setUserid($userid);
        $request = $this->getRequest();
        $form    = $this->createForm(new TagsetType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tagset_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tagset entity.
     *
     * @Route("/{id}/edit", name="tagset_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tagset entity.');
        }

        $editForm = $this->createForm(new TagsetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tagset entity.
     *
     * @Route("/{id}/update", name="tagset_update")
     * @Method("post")
     * @Template("MarcaTagBundle:Tagset:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tagset entity.');
        }

        $editForm   = $this->createForm(new TagsetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tagset'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tagset entity.
     *
     * @Route("/{id}/delete", name="tagset_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tagset entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tagset'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
