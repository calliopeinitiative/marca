<?php

namespace Marca\AssignmentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssignmentBundle\Entity\Review;
use Marca\FileBundle\Entity\File;
use Marca\AssignmentBundle\Entity\ReviewResponse;
use Marca\AssignmentBundle\Form\ReviewType;

/**
 * Review controller.
 *
 * @Route("/review")
 */
class ReviewController extends Controller
{
    /**
     * Lists all Review entities.
     *
     * @Route("/{fileid}/", name="review")
     * @Template()
     */
    public function indexAction($fileid)
    {
        $em = $this->getDoctrine()->getManager();
	$file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($fileid);

        return array(
            'reviews' => $reviews,
        );
    }

    /**
     * Finds and displays a Review entity.
     *
     * @Route("/{id}/show", name="review_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);

        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'review'      => $review,
            'delete_form' => $deleteForm->createView(),
        );
    }

      /**
     * Displays a form to create a new Review Response entity.
     *
     * @Route("/{courseid}/{fileid}/{reviewrubricid}/new", name="review_new")
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
        $review->setFile($reviewfile);
        $review->setReviewer($reviewer);
        $review->setCourse($course);
        $review->setreviewrubric($reviewrubric);
       
        for ($i = 0; $i < $cnt; $i++) { 
        $promptitem = $promptitems[$i]; 
        $reviewresponse = new ReviewResponse(); 
        $reviewresponse->setReviewPrompt($promptitem);
        $reviewresponse->setReview($review);
        $em->persist($reviewresponse);        
    }
        $em->persist($review);
        $em->flush();
        return $this->redirect($this->generateUrl('review_edit', array('id' => $review->getId())));
    }

    /**
     * Displays a form to edit an existing Review entity.
     *
     * @Route("/{id}/edit", name="review_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);

        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        $editForm = $this->createForm(new ReviewType(), $review);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'review'      => $review,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Review entity.
     *
     * @Route("/{id}/update", name="review_update")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:Review:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);

        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReviewType(), $review);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($review);
            $em->flush();

            return $this->redirect($this->generateUrl('review_show', array('id' => $id)));
        }

        return array(
            'review'      => $review,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Review entity.
     *
     * @Route("/{id}/delete", name="review_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Review entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('review'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
