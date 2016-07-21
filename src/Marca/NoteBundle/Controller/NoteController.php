<?php

namespace Marca\NoteBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\NoteBundle\Entity\Note;
use Marca\NoteBundle\Form\NoteType;

/**
 * Note controller.
 *
 * @Route("/note")
 */
class NoteController extends Controller
{

    /**
     * Create Sidebar fragment
     *
     * @Route("/{courseid}/sidebar", name="note_sidebar")
     */
    public function createSidebarAction()
    {
        return $this->render('MarcaNoteBundle::sidebar.html.twig', array( ));
    }


    /**
     * Lists all Note entities.
     *
     * @Route("/{courseid}/", name="note")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse($request);
        
        $notes = $em->getRepository('MarcaNoteBundle:Note')->findNotes($user, $course);

        return array('notes' => $notes);
    }


    /**
     * Displays a form to create a new Note entity.
     *
     * @Route("/{courseid}/new", name="note_new")
     * @Template()
     */
    public function newAction($courseid)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse($request);

        $note = new Note();
        $note->setTitle('New Note');
        $note->setUser($user);
        $note->setCourse($course);


            $em->persist($note);
            $em->flush();


        return $this->redirect($this->generateUrl('note_edit', array('id' => $note->getId(), 'courseid'=> $courseid,)));
    }


    /**
     * Displays a form to edit an existing Note entity.
     *
     * @Route("/{courseid}/{id}/edit", name="note_edit")
     * @Template()
     */
    public function editAction($id, $courseid)
    {
        $em = $this->getEm();

        $note = $em->getRepository('MarcaNoteBundle:Note')->find($id);

        if (!$note) {
            throw $this->createNotFoundException('Unable to find Note entity.');
        }
        $options = array();
        $editForm = $this->createEditForm($note, $courseid, $options);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        return array(
            'note'      => $note,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Creates a form to edit a Note entity.
     *
     * @param Note $note
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Note $note, $courseid, $options)
    {
        $form = $this->createForm(new NoteType($options), $note, array(
            'action' => $this->generateUrl('note_update', array('id' => $note->getId(),'courseid' => $courseid,)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }

    /**
     * Edits an existing Note entity.
     *
     * @Route("/{courseid}/{id}/update", name="note_update")
     * @Method("post")
     * @Template("MarcaNoteBundle:Note:edit.html.twig")
     */
    public function updateAction($id, $courseid)
    {
        $em = $this->getEm();

        $note = $em->getRepository('MarcaNoteBundle:Note')->find($id);

        if (!$note) {
            throw $this->createNotFoundException('Unable to find Note.');
        }

        $options = array();
        $editForm = $this->createEditForm($note, $courseid, $options);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($note);
            $em->flush();

            return $this->redirect($this->generateUrl('note', array('courseid' => $courseid)));
        }

        return array(
            'note'      => $note,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Note entity.
     *
     * @Route("/{courseid}/{id}/delete", name="note_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid)
    {
        $form = $this->createDeleteForm($id, $courseid);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $note = $em->getRepository('MarcaNoteBundle:Note')->find($id);

            if (!$note) {
                throw $this->createNotFoundException('Unable to find Note entity.');
            }

            $em->remove($note);
            $em->flush();
        }

            return $this->redirect($this->generateUrl('note', array('courseid' => $courseid)));
    }

    /**
     * Creates a form to delete a Note entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('note_delete', array('id' => $id,'courseid' => $courseid,)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
