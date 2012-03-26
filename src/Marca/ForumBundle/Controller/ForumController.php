<?php

namespace Marca\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\ForumBundle\Entity\Forum;
use Marca\ForumBundle\Form\ForumType;

/**
 * Forum controller.
 *
 * @Route("/forum")
 */
class ForumController extends Controller
{
    /**
     * Lists all Forum entities.
     *
     * @Route("/", name="forum")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MarcaForumBundle:Forum')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Forum entity.
     *
     * @Route("/{id}/show", name="forum_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Forum entity.
     *
     * @Route("/new", name="forum_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Forum();
        $form   = $this->createForm(new ForumType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Forum entity.
     *
     * @Route("/create", name="forum_create")
     * @Method("post")
     * @Template("MarcaForumBundle:Forum:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Forum();
        $request = $this->getRequest();
        $form    = $this->createForm(new ForumType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Forum entity.
     *
     * @Route("/{id}/edit", name="forum_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $editForm = $this->createForm(new ForumType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Forum entity.
     *
     * @Route("/{id}/update", name="forum_update")
     * @Method("post")
     * @Template("MarcaForumBundle:Forum:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $editForm   = $this->createForm(new ForumType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Forum entity.
     *
     * @Route("/{id}/delete", name="forum_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Forum entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('forum'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
