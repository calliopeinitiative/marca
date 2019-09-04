<?php

namespace Marca\JournalBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\JournalBundle\Entity\Journal;
use Marca\JournalBundle\Form\JournalType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Journal controller.
 *
 * @Route("/journal")
 */
class JournalController extends Controller
{

    /**
     * Create Sidebar fragment
     *
     * @Route("/{courseid}/sidebar", name="journal_sidebar")
     */
    public function createSidebarAction(Request $request, $courseid)
    {
        $role = $this->getCourseRole($request);
        return $this->render('MarcaJournalBundle::sidebar.html.twig', array(
            'role' => $role
        ));
    }

    /**
     * Create Subnav fragment
     *
     * @Route("/{courseid}/{user}/{userid}/subnav", name="journal_subnav")
     */
    public function createSubnavAction(Request $request, $courseid,$user,$userid)
    {
        $em = $this->getEm();
        if ($user==0){
            $user = $this->getUser();
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        }
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $role = $this->getCourseRole($request);
        return $this->render('MarcaJournalBundle::subnav.html.twig', array(
            'roll' => $roll,
            'user' => $user,
            'userid' => $userid,
            'role' => $role
        ));
    }

    /**
     * Lists all Journal entities.
     *
     * @Route("/{courseid}/{page}/{userid}/{user}/list", name="journal_list", defaults={"page" = 1,"userid" = 0,"user" = 0}))
     */
    public function indexAction(Request $request, $courseid,$page,$user,$userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        if ($user==0){
            $user = $this->getUser();
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        }


        $course = $this->getCourse($request);
        $role = $this->getCourseRole($request);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        $journals = $em->getRepository('MarcaJournalBundle:Journal')->findJournalRecent($user, $course);

        //pagination
        $paginator = $this->get('knp_paginator');
        $journal = $paginator->paginate($journals,$request->query->get('page', $page),1);

        return $this->render('MarcaJournalBundle:Journal:index.html.twig',array(
            'journal' => $journal,
            'journals' => $journals,
            'roll' => $roll,
            'role' => $role,
            'user' => $user,
            'userid' => $userid
        ));
    }


    /**
     * To use the autosave, new persists the entity and redirects to edit.
     *
     * @Route("/{courseid}/new", name="journal_new")
     */
    public function newAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse($request);

        $journal = new Journal();
        $journal->setBody('<p></p>');
        $journal->setTitle('New Journal Entry');
        $journal->setUser($user);
        $journal->setCourse($course);

        $em->persist($journal);
        $em->flush();

        return $this->redirect($this->generateUrl('journal_edit', array('id' => $journal->getId(), 'courseid'=> $courseid,)));

    }


    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{courseid}/{id}/edit", name="journal_edit")
     */
    public function editAction(Request $request, $courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        $role = $this->getCourseRole($request);
        $em = $this->getEm();
        $user = $this->getUser();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);

        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }
        elseif($user != $journal->getUser()){
            throw new AccessDeniedException();
        }

        $options = array();
        $editForm = $this->createEditForm($journal, $courseid, $options);
//        $deleteForm = $this->createDeleteForm($id, $courseid);


        return $this->render('MarcaJournalBundle:Journal:edit.html.twig',array(
            'journal'      => $journal,
            'edit_form'   => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
            'roll' => $roll,
            'role' => $role,
            'user' => $user
        ));
    }

    /**
     * Creates a form to edit a Journal entity.
     *
     * @param Journal $journal
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Journal $journal, $courseid)
    {
        $form = $this->createForm(JournalType::class, $journal, [
            'action' => $this->generateUrl('journal_update', ['id' => $journal->getId(), 'courseid' => $courseid])]);
        return $form;
    }



    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{courseid}/{id}/update", name="journal_update")
     * @Method("post")
     */
    public function updateAction(Request $request, $id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $role = $this->getCourseRole($request);
        $user = $this->getUser();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }

        $options = array();
        $editForm = $this->createEditForm($journal, $courseid, $options);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($journal);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('saved', 'Your journal was saved.  Click Edit to continue working.');


            return $this->redirect($this->generateUrl('journal_list', array('courseid'=> $courseid,)));
        }

        return $this->render('MarcaJournalBundle:Journal:edit.html.twig',array(
            'journal'      => $journal,
            'edit_form'   => $editForm->createView(),
            'roll' => $roll,
            'role' => $role,
            'user' => $user
        ));
    }


    /**
     * Saves via AJAX
     * @Route("/{courseid}/{id}/ajaxupdate", name="journal_ajax")
     * @Method("post")
     */
    public function ajaxUpdateAction (Request $request, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);

        $journal_body_temp = $request->request->get('journalBody');
        $journal_title_temp = $request->request->get('journalTitle');

        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }


        $journal->setBody($journal_body_temp);
        $journal->setTitle($journal_title_temp);
        $em->persist($journal);

        $em->flush();

        $return = "success";
        return new Response($return,200,array('Content-Type'=>'application/json'));
    }


    /**
     * @Route("delete/{id}/{courseid}", name="journal_delete", methods="DELETE")
     */
    public function journal_delete(Request $request, Journal $journal, $courseid): Response
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        $user = $this->getUser();
        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }
        elseif($user != $journal->getUser()){
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$journal->getId(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($journal);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('journal_list', array('courseid'=> $courseid,)));
    }


}
