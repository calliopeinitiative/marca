<?php

namespace Marca\NoteBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\NoteBundle\Entity\Note;
//use Marca\NoteBundle\Form\NoteType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Note controller.
 *
 * @Route("notes")
 */
class NoteController extends Controller
{
    /**
     * Lists all Calendar entities.
     *
     * @Route("/{courseid}", name="notes")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        $notes = $em->getRepository('MarcaNoteBundle:Note')->findByRoll($roll);
        return array ('notes' => $notes);
    }
    
    /**
     * Displays form to create a New Note
     * @Route("/{courseid}/new", name="new_note")
     */
    public function newAction()
    {
        $note = new Note;
        return array('note' => $note);
    }
}
