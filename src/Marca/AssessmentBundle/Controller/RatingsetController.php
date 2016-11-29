<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Marca\AssessmentBundle\Entity\Rating;
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
     * Displays a form to create a new Rating entity.
     *
     * @Route("/{courseid}/{userid}/{user}/{portfoliosetid}/new", name="ratingset_new")
     */
    public function newAction(Request $request, $courseid, $userid, $user, $portfoliosetid)
    {
        $em = $this->getEm();
        $course = $this->getCourse($request);
        $rater =  $this->getUser();
        $next_port = $user;
        $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        $portfolioset = $em->getRepository('MarcaPortfolioBundle:Portfolioset')->find($portfoliosetid);
        $objectives = $course->getAssessmentset()->getObjectives();
        $cnt = count($objectives);
        $ratingset = new Ratingset();
        //ratingset user is the owner of the portfolio
        $ratingset->setUser($user);
        $ratingset->setRater($rater);
        $ratingset->setCourse($course);
        $ratingset->setPortfolioset($portfolioset);

        for ($i = 0; $i < $cnt; $i++) {
            $objective = $objectives[$i];
            $scale = $objective->getScale();
            $default_scaleitem = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->findDefault($scale->getId());
            $scaleitem = $default_scaleitem;
            $rating = new Rating();
            $rating->setRatingset($ratingset);
            $rating->setObjective($objective);
            $rating->setScaleitem($scaleitem);
            $em->persist($rating);
        }
        $em->persist($ratingset);
        $em->flush();
        return $this->redirect($this->generateUrl('ratingset_edit', array('id' => $ratingset->getId(),'courseid' => $courseid, 'userid' => $userid, 'user'=> $next_port )));
    }

    /**
     * Displays a form to edit an existing Ratingset entity.
     *
     * @Route("/{courseid}/{userid}/{user}/{id}/edit", name="ratingset_edit")
     */
    public function editAction(Request $request, $courseid, $id, $userid)
    {
        $em = $this->getEm();
        $course = $this->getCourse($request);
        $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        $assessmentset = $course->getAssessmentset();

        $ratingset = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

        if (!$ratingset) {
            throw $this->createNotFoundException('Unable to find Ratingset entity.');
        }

        $objectives = $course->getAssessmentset()->getObjectives();
        $objective = $objectives[0];
        $scale = $objective->getScale()->getId();
        $options = array('scale' => $scale);
        $editForm = $this->createForm(new RatingsetType($options), $ratingset);
        $deleteForm = $this->createDeleteForm($courseid,$id,$userid,$user);

        return $this->render('MarcaAssessmentBundle:Ratingset:edit.html.twig', array(
            'ratingset'      => $ratingset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'assessmentset' => $assessmentset,
            'user' => $user,
        ));
    }

    /**
     * Edits an existing Ratingset entity.
     *
     * @Route("/{courseid}/{userid}/{user}/{id}/update", name="ratingset_update")
     * @Method("post")
     */
    public function updateAction(Request $request, $courseid,$id,$userid,$user)
    {
        $em = $this->getEm();
        $course = $this->getCourse($request);
        $entity = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ratingset entity.');
        }
        $objectives = $course->getAssessmentset()->getObjectives();
        $objective = $objectives[0];
        $scale = $objective->getScale()->getId();
        $options = array('scale' => $scale);
        $editForm   = $this->createForm(new RatingsetType($options), $entity);
        $deleteForm = $this->createDeleteForm($courseid,$id,$userid,$user);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio_user', array('courseid' => $courseid, 'userid' => $userid,'user' => $user)));
        }

        return $this->render('MarcaAssessmentBundle:Ratingset:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Ratingset entity.
     *
     * @Route("/{courseid}/{userid}/{user}/{id}/delete", name="ratingset_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $courseid,$id,$userid,$user)
    {
        $form = $this->createDeleteForm($courseid,$id,$userid,$user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $entity = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ratingset entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('portfolio_user', array('courseid' => $courseid, 'userid' => $userid,'user' => $user)));
    }

    /**
     * Creates a form to delete a Journal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($courseid,$id,$userid,$user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ratingset_delete', array('id' => $id,'courseid' => $courseid,'userid' => $userid,'user' => $user,)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
