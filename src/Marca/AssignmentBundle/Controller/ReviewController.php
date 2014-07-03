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
use Marca\AssignmentBundle\Entity\ReviewRubric;
use Marca\AssignmentBundle\Form\ReviewType;
use Marca\AssignmentBundle\Form\selectRubricType;

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
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($fileid);

        return array(
            'reviews' => $reviews,
        );
    }

    /**
     * Lists all Review entities.
     *
     * @Route("/{fileid}/index_ajax", name="review_index_ajax")
     * @Template()
     */
    public function index_ajaxAction($fileid)
    {
        $em = $this->getDoctrine()->getManager();
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
     * Finds and displays a Review entity.
     *
     * @Route("/{courseid}/{id}/show_ajax", name="review_show_ajax")
     * @Template()
     */
    public function show_ajaxAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getCourseRole();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);


        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        return array(
            'review'      => $review,
            'role'      => $role,
        );
    }

    /**
     * Finds and displays a Review entity.
     *
     * @Route("/{courseid}/{id}/refresh_ajax", name="review_refresh_ajax")
     * @Template()
     */
    public function refresh_ajaxAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getCourseRole();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);
        $fileid = $review->getFile()->getId();
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($fileid);


        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        return array(
            'reviews' => $reviews,
            'review'      => $review,
            'role'      => $role,
        );
    }


    /**
     * Display form to selects Review Rubric for new Reviews
     * @Route("/{courseid}/{fileid}/selectrubric", name="selectrubric")
     * @Template()
     */
    public function displaySelectAction($courseid, $fileid)
    {
        $rubric = new Review();
        $select_form = $this->createFormBuilder($rubric)->add('reviewrubric', 'entity', array('class'=>'MarcaAssignmentBundle:ReviewRubric', 'property'=>'name','required'  => true,'label'  => 'Select Rubric for your Review', 'expanded' => false,'attr' => array('class' => 'form-control'),))->getForm();
        return array('form'=>$select_form->createView(), 'courseid' => $courseid, 'fileid'=>$fileid);
    }
    
    /**
     * Display form to selects Review Rubric for new Reviews
     * @Route("/{courseid}/{fileid}/assignrubric", name="assignrubric")
     * @Template()
     * @Method("POST")
     */
    public function assignAction($courseid, $fileid)
    {
        //$select_form = $this->createFormBuilder()->add('reviewrubric','entity', array('class'=>'MarcaAssignmentBundle:ReviewRubric', 'property'=>'name'))->getForm();
        $request = $this->get('request');
        $postData = $request->request->get('form');
        $rubric = $postData['reviewrubric'];
        //$rubricid = $rubric->getId();
        return $this->redirect($this->generateUrl('review_new', array('courseid'=>$courseid, 'fileid'=>$fileid, 'reviewrubricid'=>$rubric)));
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
        return $this->redirect($this->generateUrl('review_edit_ajax', array('courseid'=>$courseid, 'id' => $review->getId())));
    }

    /**
     * Displays a form to edit an existing Review entity.
     *
     * @Route("/{courseid}/{id}/edit", name="review_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getCourseRole();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);

        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        $editForm = $this->createForm(new ReviewType(), $review);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'role'      => $role,
            'review'      => $review,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Review entity.
     *
     * @Route("/{courseid}/{id}/edit_ajax", name="review_edit_ajax")
     * @Template()
     */
    public function edit_ajaxAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getCourseRole();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);
        $options = array('scaleid' => '1');

        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        $editForm = $this->createForm(new ReviewType($options), $review);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'role'      => $role,
            'review'      => $review,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Review entity.
     *
     * @Route("/{courseid}/{id}/update", name="review_update")
     * @Method("POST")
     * @Template("MarcaAssignmentBundle:Review:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $courseid)
    {
        $em = $this->getDoctrine()->getManager();

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);
        $options = array('scaleid' => '1');

        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReviewType($options), $review);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($review);
            $em->flush();

            return $this->redirect($this->generateUrl('review_refresh_ajax', array('courseid' => $courseid,'id' => $id)));
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
     * @Route("/{courseid}/{id}/delete", name="review_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id, $courseid)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);
            $file= $review->getFile();
            $fileid=$file->getId();

            if (!$review) {
                throw $this->createNotFoundException('Unable to find Review entity.');
            }

            $em->remove($review);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('doc_show', array('courseid' => $courseid,'id' => $fileid,'view' => 'app')));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
