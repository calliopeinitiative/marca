<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssessmentBundle\Entity\Rating;
use Marca\AssessmentBundle\Entity\Ratingset;
use Marca\AssessmentBundle\Form\RatingType;

/**
 * Rating controller.
 *
 * @Route("/rating")
 */
class RatingController extends Controller
{
    /**
     * Lists all Rating entities.
     *
     * @Route("/", name="rating")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('MarcaAssessmentBundle:Rating')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Rating entity.
     *
     * @Route("/{id}/show", name="rating_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaAssessmentBundle:Rating')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rating entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Rating entity.
     *
     * @Route("/{courseid}/{userid}/{user}/new", name="rating_new")
     * @Template()
     */
    public function newAction($courseid, $userid, $user)
    {
        $em = $this->getEm();
        $course = $this->getCourse();
        $rater =  $this->getUser();
        $next_port = $user;
        $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        $objectives = $course->getAssessmentset()->getObjectives();
        $cnt = count($objectives);
        $ratingset = new Ratingset();
        //ratingset user is the owner of the portfolio
        $ratingset->setUser($user);
        $ratingset->setRater($rater);
        $ratingset->setCourse($course);
        
        for ($i = 0; $i < $cnt; $i++) { 
        $objective = $objectives[$i]; 
        $scale = $objective->getScale();
        $scaleitems = $scale->getScaleitems();
        $default_value = $scaleitems[1]->getValue();
        $rating = new Rating(); 
        $rating->setRatingset($ratingset);
        $rating->setObjective($objective);
        $rating->setValue($default_value);
        $em->persist($rating);        
        }
        $em->persist($ratingset);
        $em->flush();
        return $this->redirect($this->generateUrl('portfolio_user', array('courseid' => $courseid, 'userid' => $userid, 'user'=> $next_port )));
    }


    /**
     * Displays a form to edit an existing Rating entity.
     *
     * @Route("/{id}/edit", name="rating_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaAssessmentBundle:Rating')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rating entity.');
        }

        $editForm = $this->createForm(new RatingType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Rating entity.
     *
     * @Route("/{id}/update", name="rating_update")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Rating:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaAssessmentBundle:Rating')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rating entity.');
        }

        $editForm   = $this->createForm(new RatingType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rating_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Rating entity.
     *
     * @Route("/{id}/delete", name="rating_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaAssessmentBundle:Rating')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rating entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rating'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
