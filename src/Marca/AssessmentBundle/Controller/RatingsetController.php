<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Template()
     */
    public function newAction($courseid, $userid, $user, $portfoliosetid)
    {
        $em = $this->getEm();
        $course = $this->getCourse();
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
        $scaleitems = $scale->getScaleitems();
        $scaleitem = $scaleitems[1];
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
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();
        $course = $this->getCourse();
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
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'ratingset'      => $ratingset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'assessmentset' => $assessmentset,
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
        $em = $this->getEm();
        $course = $this->getCourse();
        $entity = $em->getRepository('MarcaAssessmentBundle:Ratingset')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ratingset entity.');
        }
        $objectives = $course->getAssessmentset()->getObjectives();
        $objective = $objectives[0];
        $scale = $objective->getScale()->getId();
        $options = array('scale' => $scale);
        $editForm   = $this->createForm(new RatingsetType($options), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

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
     * @Route("/{courseid}/{userid}/{user}/{id}/delete", name="ratingset_delete")
     * @Method("post")
     */
    public function deleteAction($courseid,$id,$userid,$user)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

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

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
