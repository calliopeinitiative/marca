<?php

namespace Marca\AssignmentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssignmentBundle\Entity\ReviewResponse;
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
     * Displays a form to create a new Review Response entity.
     *
     * @Route("/{courseid}/{fileid}/{reviewrubricid}/new", name="reviewresponse_new")
     * @Template()
     */
    public function newAction($courseid, $reviewrubricid, $fileid)
    {
        $em = $this->getEm();
        $course = $this->getCourse();
        $reviewer =  $this->getUser();
        $reviewrubric = $em->getRepository('MarcaAssignmentBundle:ReviewRubric')->find($reviewrubricid);
        $reviewfile = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $promptitems = $reviewrubric->getPromptitems();
        
        $review = new Review(); 
        $cnt = count($promptitems);
        $review->setFile($fileid);
        $review->setReviewer($reviewer);
        $review->setCourse($course);
        $review->setreviewrubric($reviewrubric);
       
        for ($i = 0; $i < $cnt; $i++) { 
        $promptitem = $promptitems[$i]; 
        $reviewresponse = new ReviewResponse(); 
        $reviewresponse->setreviewPrompt($promptitem);
        $em->persist($reviewresponse);        
    }
        $em->flush();
        return $this->redirect($this->generateUrl('reviewresponse_edit', array('id' => $reviewresponse->getId(),'courseid' => $courseid, 'user' => $reviewer)));
    }
    
    
    
    /**
     * Creates a new ReviewResponse entity.
     *
     * @Route("/create", name="reviewresponse_create")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:ReviewResponse:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ReviewResponse();
        $form = $this->createForm(new ReviewResponseType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('reviewresponse_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReviewResponse entity.
     *
     * @Route("/{docid}/{/{id}/edit", name="reviewresponse_edit")
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
        $editForm->bind($request);

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
        $form->bind($request);

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
