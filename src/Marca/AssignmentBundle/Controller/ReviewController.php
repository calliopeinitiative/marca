<?php

namespace Marca\AssignmentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Route("/{fileid}/index_ajax", name="review_index_ajax")
     */
    public function index_ajaxAction($fileid)
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($fileid);

        return $this->render('MarcaAssignmentBundle:Review:index_ajax.html.twig', array(
            'reviews' => $reviews,
        ));
    }


    /**
     * Finds and displays a Review entity.
     *
     * @Route("/{courseid}/{id}/show_ajax", name="review_show_ajax")
     */
    public function show_ajaxAction($id, $courseid)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getCourseRole($request);

        $course= $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);
        $user=$review->getFile()->getUser();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        $rollid = $roll->getId();


        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        return $this->render('MarcaAssignmentBundle:Review:show_ajax.html.twig', array(
            'review'      => $review,
            'role'      => $role,
            'rollid'      => $rollid,
        ));
    }

    /**
     * Finds and displays a Review entity.
     *
     * @Route("/{courseid}/{id}/refresh_ajax", name="review_refresh_ajax")
     */
    public function refresh_ajaxAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getCourseRole($request);

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);
        $fileid = $review->getFile()->getId();
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($fileid);


        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        return $this->render('MarcaAssignmentBundle:Review:refresh_ajax.html.twig', array(
            'reviews' => $reviews,
            'review'      => $review,
            'role'      => $role,
        ));
    }


    /**
     * Display form to selects Review Rubric for new Reviews
     * @Route("/{courseid}/{fileid}/selectrubric", name="selectrubric")
     */
    public function displaySelectAction($courseid, $fileid)
    {
        $rubric = new Review();
        $select_form = $this->createFormBuilder($rubric)->add('reviewrubric', 'entity', array('class'=>'MarcaAssignmentBundle:ReviewRubric', 'property'=>'name','required'  => true,'label'  => 'Select Rubric for your Review', 'expanded' => false,'attr' => array('class' => 'form-control'),))->getForm();
        return $this->render('MarcaAssignmentBundle:Review:displaySelect.html.twig', array(
            'form'=>$select_form->createView(),
            'courseid' => $courseid,
            'fileid'=>$fileid
        ));
    }
    
    /**
     * Display form to selects Review Rubric for new Reviews
     * @Route("/{courseid}/{fileid}/assignrubric", name="assignrubric")
     * @Method("POST")
     */
    public function assignAction($courseid, $fileid)
    {
        $request = $this->get('request');
        $postData = $request->request->get('form');
        $rubric = $postData['reviewrubric'];
        return $this->redirect($this->generateUrl('review_new', array('courseid'=>$courseid, 'fileid'=>$fileid, 'reviewrubricid'=>$rubric)));
    }

      /**
     * Displays a form to create a new Review Response entity.
     *
     * @Route("/{courseid}/{fileid}/{reviewrubricid}/new", name="review_new")
     */
    public function newAction($courseid, $reviewrubricid, $fileid)
    {
        $em = $this->getEm();
        $course = $this->getCourse($request);
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
     * @Route("/{courseid}/{id}/edit_ajax", name="review_edit_ajax")
     */
    public function edit_ajaxAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $role = $this->getCourseRole($request);

        $review = $em->getRepository('MarcaAssignmentBundle:Review')->find($id);
        $options = array('scaleid' => '1');

        if (!$review) {
            throw $this->createNotFoundException('Unable to find Review entity.');
        }

        $editForm = $this->createForm(new ReviewType($options), $review);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaAssignmentBundle:Review:edit_ajax.html.twig', array(
            'role'      => $role,
            'review'      => $review,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Review entity.
     *
     * @Route("/{courseid}/{id}/update", name="review_update")
     * @Method("POST")
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

        return $this->render('MarcaAssignmentBundle:Review:edit_ajax.html.twig', array(
            'review'      => $review,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
