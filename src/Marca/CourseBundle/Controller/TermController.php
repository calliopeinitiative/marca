<?php

namespace Marca\CourseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Term;
use Marca\CourseBundle\Form\TermType;

/**
 * Term controller.
 *
 * @Route("/term")
 */
class TermController extends Controller
{
    /**
     * Lists all Term entities.
     *
     * @Route("/", name="term")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $terms = $em->getRepository('MarcaCourseBundle:Term')->findAll();

        return array('terms' => $terms);
    }

    /**
     * Finds and displays a Term entity.
     *
     * @Route("/{id}/show", name="term_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $term = $em->getRepository('MarcaCourseBundle:Term')->find($id);

        if (!$term) {
            throw $this->createNotFoundException('Unable to find Term entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'term'      => $term,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Term entity.
     *
     * @Route("/new", name="term_new")
     * @Template()
     */
    public function newAction()
    {
        
        $term = new Term();
        $form   = $this->createForm(new TermType(), $term);

        return array(
            'term' => $term,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Term entity.
     *
     * @Route("/create", name="term_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Term:new.html.twig")
     */
    public function createAction()
    {
        
        $term  = new Term();
        $request = $this->getRequest();
        $form    = $this->createForm(new TermType(), $term);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($term);
            $em->flush();

            return $this->redirect($this->generateUrl('term'));
            
        }

        return array(
            'term' => $term,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Term entity.
     *
     * @Route("/{id}/edit", name="term_edit")
     * @Template()
     */
    public function editAction($id)
    {
        
        $em = $this->getEm();

        $term = $em->getRepository('MarcaCourseBundle:Term')->find($id);

        if (!$term) {
            throw $this->createNotFoundException('Unable to find Term entity.');
        }

        $editForm = $this->createForm(new TermType(), $term);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'term'      => $term,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Term entity.
     *
     * @Route("/{id}/update", name="term_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Term:edit.html.twig")
     */
    public function updateAction($id)
    {
        
        $em = $this->getEm();

        $term = $em->getRepository('MarcaCourseBundle:Term')->find($id);

        if (!$term) {
            throw $this->createNotFoundException('Unable to find Term entity.');
        }

        $editForm   = $this->createForm(new TermType(), $term);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($term);
            $em->flush();

            return $this->redirect($this->generateUrl('term'));
        }

        return array(
            'term'      => $term,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Term entity.
     *
     * @Route("/{id}/delete", name="term_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $term = $em->getRepository('MarcaCourseBundle:Term')->find($id);

            if (!$term) {
                throw $this->createNotFoundException('Unable to find Term entity.');
            }

            $em->remove($term);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('term'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
