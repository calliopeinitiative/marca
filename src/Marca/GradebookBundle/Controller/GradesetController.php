<?php

namespace Marca\GradebookBundle\Controller;

use Marca\GradebookBundle\Entity\Category;
use Marca\GradebookBundle\Entity\Gradeset;
use Marca\GradebookBundle\Form\GradesetType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gradeset controller.
 *
 * @Route("/gradeset")
 */
class GradesetController extends Controller
{

    /**
     * Lists all Gradeset entities.
     *
     * @Route("/", name="gradeset")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $gradesets = $em->getRepository('MarcaGradebookBundle:Gradeset')->findAll();

        return array(
            'gradesets' => $gradesets,
        );
    }

    /**
     * Creates a form to create a Gradeset entity.
     *
     * @param Gradeset $gradeset The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Gradeset $gradeset)
    {
        $form = $this->createForm(new GradesetType(), $gradeset, array(
            'action' => $this->generateUrl('gradeset_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new Gradeset entity.
     *
     * @Route("/{courseid}/new", name="gradeset_new")
     * @Method("POST")
     * @Template()
     */
    public function newAction($courseid)
    {
        $em = $this->getEm();
        $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->findByCourse($courseid);

        if ($gradeset) {
            return $this->redirect($this->generateUrl('course_show', array('id' => $courseid)));
        }

        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        $gradeset = new Gradeset();
        $gradeset->setName('Default');
        $gradeset->setCourse($course);

        $category1 = new Category();
        $category1->setName('Paper 1');
        $category1->setPercent('15');
        $category1->setGradeset($gradeset);

        $category2 = new Category();
        $category2->setName('Paper 2');
        $category2->setPercent('20');
        $category2->setGradeset($gradeset);

        $category3 = new Category();
        $category3->setName('Paper 3');
        $category3->setPercent('20');
        $category3->setGradeset($gradeset);

        $category4 = new Category();
        $category4->setName('Participation');
        $category4->setPercent('15');
        $category4->setGradeset($gradeset);

        $category5 = new Category();
        $category5->setName('Portfolio');
        $category5->setPercent('30');
        $category5->setGradeset($gradeset);

        $em->persist($gradeset);
        $em->persist($category1);
        $em->persist($category2);
        $em->persist($category3);
        $em->persist($category4);
        $em->persist($category5);
        $em->flush();

        return $this->redirect($this->generateUrl('gradeset_show', array('id' => $gradeset->getId())));
        
    }

    /**
     * Finds and displays a Gradeset entity.
     *
     * @Route("/{id}", name="gradeset_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

        if (!$gradeset) {
            throw $this->createNotFoundException('Unable to find Gradeset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'gradeset'      => $gradeset,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Gradeset entity.
     *
     * @Route("/{id}/edit", name="gradeset_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

        if (!$gradeset) {
            throw $this->createNotFoundException('Unable to find Gradeset entity.');
        }

        $editForm = $this->createEditForm($gradeset);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'gradeset'      => $gradeset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Gradeset entity.
    *
    * @param Gradeset $gradeset The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Gradeset $gradeset)
    {
        $form = $this->createForm(new GradesetType(), $gradeset, array(
            'action' => $this->generateUrl('gradeset_update', array('id' => $gradeset->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Gradeset entity.
     *
     * @Route("/{id}", name="gradeset_update")
     * @Method("PUT")
     * @Template("MarcaGradebookBundle:Gradeset:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getEm();

        $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

        if (!$gradeset) {
            throw $this->createNotFoundException('Unable to find Gradeset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($gradeset);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('gradeset_edit', array('id' => $id)));
        }

        return array(
            'gradeset'      => $gradeset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Gradeset entity.
     *
     * @Route("/{id}", name="gradeset_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $gradeset = $em->getRepository('MarcaGradebookBundle:Gradeset')->find($id);

            if (!$gradeset) {
                throw $this->createNotFoundException('Unable to find Gradeset entity.');
            }

            $em->remove($gradeset);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gradeset'));
    }

    /**
     * Creates a form to delete a Gradeset entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gradeset_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
