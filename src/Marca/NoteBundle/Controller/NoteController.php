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
     * Lists all Note entities.
     *
     * @Route("/{courseid}/", name="note")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        
        $notes = $em->getRepository('MarcaNoteBundle:Note')->findNotes($user, $course);

        return array('notes' => $notes);
    }

    /**
     * Finds and displays a Note entity.
     *
     * @Route("/{courseid}/{id}/show", name="note_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $note = $em->getRepository('MarcaNoteBundle:Note')->find($id);

        if (!$note) {
            throw $this->createNotFoundException('Unable to find Note note.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'note'      => $note,
            'delete_form' => $deleteForm->createView(),        );
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
        $course = $this->getCourse();

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
    public function editAction($id)
    {
        $em = $this->getEm();

        $note = $em->getRepository('MarcaNoteBundle:Note')->find($id);


        if (!$note) {
            throw $this->createNotFoundException('Unable to find Note entity.');
        }

        $editForm = $this->createForm(new NoteType(), $note);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'note'      => $note,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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

        $editForm   = $this->createForm(new NoteType(), $note);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

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
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

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

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
