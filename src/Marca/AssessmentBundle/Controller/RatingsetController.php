<?php

namespace Marca\AssessmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssessmentBundle\Entity\Ratingset;
use Marca\AssessmentBundle\Form\RatingsetType;

/**
 * Ratingset controller.
 *
 * @Route("/ratingset")
 */
class RatingsetController extends Controller
{
    /**
     * Lists all Ratingset entities.
     *
     * @Route("/", name="ratingset")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MarcaAssessmentBundle:Ratingset')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Ratingset entity.
     *
     * @Route("/{id}/show", name="ratingset_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ratingset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Ratingset entity.
     *
     * @Route("/new", name="ratingset_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ratingset();
        $form   = $this->createForm(new RatingsetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Ratingset entity.
     *
     * @Route("/create", name="ratingset_create")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Ratingset:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Ratingset();
        $request = $this->getRequest();
        $form    = $this->createForm(new RatingsetType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ratingset_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Ratingset entity.
     *
     * @Route("/{courseid}/{userid}/{user}/{id}/edit", name="ratingset_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ratingset entity.');
        }

        $editForm = $this->createForm(new RatingsetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Ratingset entity.
     *
     * @Route("/{courseid}/{userid}/{user}/{id}/update", name="ratingset_update")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Ratingset:edit.html.twig")
     */
    public function updateAction($courseid,$id,$userid,$user)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ratingset entity.');
        }

        $editForm   = $this->createForm(new RatingsetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio_user', array('courseid' => $courseid, 'userid' => $userid,'user' => $user)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ratingset entity.
     *
     * @Route("/{id}/delete", name="ratingset_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ratingset entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ratingset'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
