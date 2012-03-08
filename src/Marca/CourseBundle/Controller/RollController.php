<?php

namespace Marca\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Roll;
use Marca\CourseBundle\Form\RollType;

/**
 * Roll controller.
 *
 * @Route("/roll")
 */
class RollController extends Controller
{
    /**
     * Lists all Roll entities.
     *
     * @Route("/", name="roll")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $dql1 = "SELECT p.lastname,p.firstname,r.role,r.status,r.id from MarcaCourseBundle:Roll r JOIN r.profile p ORDER BY p.lastname,p.firstname";
        $entities = $em->createQuery($dql1)->getResult();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Roll entity.
     *
     * @Route("/{id}/show", name="roll_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Roll entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Roll entity.
     *
     * @Route("/new", name="roll_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Roll();
        $form   = $this->createForm(new RollType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Roll entity.
     *
     * @Route("/create", name="roll_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Roll:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Roll();
        $request = $this->getRequest();
        $form    = $this->createForm(new RollType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('roll_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Roll entity.
     *
     * @Route("/{id}/edit", name="roll_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Roll entity.');
        }

        $editForm = $this->createForm(new RollType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Roll entity.
     *
     * @Route("/{id}/update", name="roll_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Roll:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Roll entity.');
        }

        $editForm   = $this->createForm(new RollType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('roll_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Roll entity.
     *
     * @Route("/{id}/delete", name="roll_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaCourseBundle:Roll')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Roll entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('roll'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
