<?php

namespace Marca\AssignmentBundle\Controller;

use Marca\AssignmentBundle\Entity\ReviewRubric;
use Marca\AssignmentBundle\Form\ReviewRubricType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReviewRubric controller.
 *
 * @Route("/reviewrubric")
 */
class ReviewRubricController extends Controller
{
    /**
     * Lists all ReviewRubric entities.
     *
     * @Route("/", name="reviewrubric")
     * @Template("MarcaAssignmentBundle:ReviewRubric:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $rubrics = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->findAll();
        $scales = $em->getRepository('MarcaAssessmentBundle:Scale')->findAll();

        return array(
            'rubrics' => $rubrics,
            'scales' => $scales
        );
    }

    /**
     * Finds and displays a ReviewRubric entity.
     *
     * @Route("/{id}/show", name="reviewrubric_show")
     * @Template("MarcaAssignmentBundle:ReviewRubric:show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

        if (!$reviewrubric) {
            throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'reviewrubric'      => $reviewrubric,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new ReviewRubric entity.
     *
     * @Route("/new", name="reviewrubric_new")
     * @Template("MarcaAssignmentBundle:ReviewRubric:new.html.twig")
     */
    public function newAction()
    {
        $reviewrubric = new ReviewRubric();
        $reviewrubric->setShared(0);
        $form   = $this->createForm(ReviewRubricType::class, $reviewrubric);

        return array(
            'reviewrubric' => $reviewrubric,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ReviewRubric entity.
     *
     * @Route("/create", name="reviewrubric_create")
     */
    public function createAction(Request $request)
    {
        
        $entity  = new ReviewRubric();
        $form = $this->createForm(ReviewRubricType::class, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReviewRubric entity.
     *
     * @Route("/{id}/edit", name="reviewrubric_edit")
     * @Template("MarcaAssignmentBundle:ReviewRubric:edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

        if (!$reviewrubric) {
            throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
        }

        $editForm = $this->createForm(ReviewRubricType::class, $reviewrubric);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'reviewrubric'      => $reviewrubric,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ReviewRubric entity.
     *
     * @Route("/{id}/update", name="reviewrubric_update")
     * @Template("MarcaAssignmentBundle:ReviewRubric:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getEm();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

        if (!$reviewrubric) {
            throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(ReviewRubricType::class, $reviewrubric);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($reviewrubric);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewrubric_show', array('id' => $id)));
        }

        return array(
            'reviewrubric'      => $reviewrubric,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

        /**
     * Displays a form to delete an existing ReviewRubric entity.
     *
     * @Route("/{id}/delete_modal", name="reviewrubric_delete_modal")
     * @Template("MarcaAssignmentBundle:ReviewRubric:delete_modal.html.twig")
     */
    public function delete_modalAction($id)
    {
        $em = $this->getEm();

        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

        if (!$reviewrubric) {
            throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'reviewrubric'      => $reviewrubric,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Deletes a ReviewRubric entity.
     *
     * @Route("/{id}/delete", name="reviewrubric_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $entity = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReviewRubric entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reviewrubric'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}


