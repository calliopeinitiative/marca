<?php

namespace Marca\CourseBundle\Controller;

use Marca\CourseBundle\Entity\Term;
use Marca\CourseBundle\Form\TermType;
use Marca\HomeBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
     * @Template("MarcaCourseBundle:Term:index.html.twig")
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
     * @Template("MarcaCourseBundle:Term:show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $term = $em->getRepository('MarcaCourseBundle:Term')->find($id);
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findCoursesByTerm($term);
        if (!$term) {
            throw $this->createNotFoundException('Unable to find Term entity.');
        }


        return array(
            'term'      => $term,
            'courses' => $courses,     );
    }

    /**
     * Displays a form to create a new Term entity.
     *
     * @Route("/new", name="term_new")
     * @Template("MarcaCourseBundle:Term:new.html.twig")
     */
    public function newAction()
    {
        
        $term = new Term();
        $form   = $this->createForm(TermType::class, $term);

        return array(
            'term' => $term,
            'form'   => $form->createView()
        );
    }
    
    /**
     * Displays a modal form to create a new Term entity.
     *
     * @Route("/{instId}/new_modal", name="term_new_modal")
     * @Template("MarcaCourseBundle:Term:new_modal.html.twig")
     */
    public function newModalAction($instId)
    {
        
        $term = new Term();
        $em = $this->getEm();
        $institution = $em->getRepository('MarcaAdminBundle:Institution')->find($instId);
        $term->setInstitution($institution);
        //One is active status, someday we have to figure out how to get that constant in the controller
        $term->setStatus(1);
        $form   = $this->createForm(TermType::class, $term);

        return array(
            'term' => $term,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Term entity.
     *
     * @Route("/create", name="term_create")
     * @Template("MarcaCourseBundle:Term:new.html.twig")
     */
    public function createAction(Request $request)
    {
        
        $term  = new Term();
        $form    = $this->createForm(TermType::class, $term);
        $form->handleRequest($request);

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
     * @Template("MarcaCourseBundle:Term:edit.html.twig")
     */
    public function editAction($id)
    {
        
        $em = $this->getEm();

        $term = $em->getRepository('MarcaCourseBundle:Term')->find($id);

        if (!$term) {
            throw $this->createNotFoundException('Unable to find Term entity.');
        }

        $editForm = $this->createForm(TermType::class, $term);
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
     * @Template("MarcaCourseBundle:Term:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        
        $em = $this->getEm();

        $term = $em->getRepository('MarcaCourseBundle:Term')->find($id);

        if (!$term) {
            throw $this->createNotFoundException('Unable to find Term entity.');
        }

        $editForm   = $this->createForm(TermType::class, $term);
        $deleteForm = $this->createDeleteForm($id);


        $editForm->handleRequest($request);

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
     */
    public function deleteAction(Request $request, $id)
    {

        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

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
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
