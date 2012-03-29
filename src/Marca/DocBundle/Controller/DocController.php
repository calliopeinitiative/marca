<?php

namespace Marca\DocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\DocBundle\Entity\Doc;
use Marca\DocBundle\Form\DocType;

/**
 * Doc controller.
 *
 * @Route("/doc")
 */
class DocController extends Controller
{
    /**
     * Lists all Doc entities.
     *
     * @Route("/", name="doc")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MarcaDocBundle:Doc')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Doc entity.
     *
     * @Route("/{id}/show", name="doc_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaDocBundle:Doc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Doc entity.
     *
     * @Route("/new", name="doc_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Doc();
        $form   = $this->createForm(new DocType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Doc entity.
     *
     * @Route("/create", name="doc_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Doc();
        $request = $this->getRequest();
        $form    = $this->createForm(new DocType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('doc_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Doc entity.
     *
     * @Route("/{id}/edit", name="doc_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaDocBundle:Doc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $editForm = $this->createForm(new DocType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Doc entity.
     *
     * @Route("/{id}/update", name="doc_update")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaDocBundle:Doc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $editForm   = $this->createForm(new DocType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('doc_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Doc entity.
     *
     * @Route("/{id}/delete", name="doc_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaDocBundle:Doc')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Doc entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('doc'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
