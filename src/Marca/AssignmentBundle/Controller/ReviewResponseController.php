<?php

namespace Marca\AssignmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssignmentBundle\Entity\ReviewResponse;
use Marca\AssignmentBundle\Entity\Review;
use Marca\AssignmentBundle\Form\ReviewResponseType;

/**
 * ReviewResponse controller.
 *
 * @Route("/reviewresponse")
 */
class ReviewResponseController extends Controller
{
    /**
     * Lists all ReviewResponse entities.
     *
     * @Route("/", name="reviewresponse")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MarcaAssignmentBundle:ReviewResponse')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a ReviewResponse entity.
     *
     * @Route("/{id}/show", name="reviewresponse_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:ReviewResponse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReviewResponse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReviewResponse entity.
     *
     * @Route("/{id}/edit", name="reviewresponse_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:ReviewResponse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReviewResponse entity.');
        }

        $editForm = $this->createForm(new ReviewResponseType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ReviewResponse entity.
     *
     * @Route("/{id}/update", name="reviewresponse_update")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:ReviewResponse:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MarcaAssignmentBundle:ReviewResponse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReviewResponse entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReviewResponseType(), $entity);
        $editForm>submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewresponse_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ReviewResponse entity.
     *
     * @Route("/{id}/delete", name="reviewresponse_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form>submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcaAssignmentBundle:ReviewResponse')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReviewResponse entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reviewresponse'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
