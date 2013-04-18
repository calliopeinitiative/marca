<?php

namespace Marca\JournalBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Marca\JournalBundle\Entity\Journal;
use Marca\JournalBundle\Form\JournalType;

/**
 * Journal controller.
 *
 * @Route("/journal")
 */
class JournalController extends Controller
{
    /**
     * Lists all Journal entities.
     *
     * @Route("/{courseid}/", name="journal")
     * @Template()
     */
    public function indexAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $role = $this->getCourseRole();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->findJournalRecent($user, $course);
        
        //pagination
        $paginator = $this->get('knp_paginator');
        $journal = $paginator->paginate($journal,$this->get('request')->query->get('page', 1),5);
        
        return array('journal' => $journal, 'roll' => $roll, 'role' => $role);
    }
    
    /**
     * Lists all Journal entities.
     *
     * @Route("/{courseid}/{userid}/{user}/journal_by_user", name="journal_user")
     * @Template("MarcaJournalBundle:Journal:index.html.twig")
     */
    public function indexByUserAction($courseid, $userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        $course = $this->getCourse();
        $role = $this->getCourseRole();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->findJournalRecent($user, $course);
        
        //pagination
        $paginator = $this->get('knp_paginator');
        $journal = $paginator->paginate($journal,$this->get('request')->query->get('page', 1),5);
        
        return array('journal' => $journal, 'roll' => $roll, 'user' => $user, 'role' => $role);
    }    

    /**
     * Finds and displays a Journal entity.
     *
     * @Route("/{courseid}/{id}/show", name="journal_show")
     * @Template()
     */
    public function showAction($courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }
        
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'journal'      => $journal,
            'delete_form' => $deleteForm->createView(), 
            'roll' => $roll, 
            'role' => $role      );
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/{courseid}/new", name="journal_new")
     * @Template()
     */
    public function newAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        $journal = new Journal();
        $journal->setBody('<p></p>');
        
        $form   = $this->createForm(new JournalType(), $journal);
        
        return array(
            'journal' => $journal,
            'form'   => $form->createView(),
            'roll' => $roll, 
            'role' => $role 
        );
    }

    /**
     * Creates a new Journal entity.
     *
     * @Route("/{courseid}/create", name="journal_create")
     * @Method("post")
     * @Template("MarcaJournalBundle:Journal:new.html.twig")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $user = $this->getUser();
        
        $course = $this->getCourse();
        $journal  = new Journal();
        $journal->setUser($user);
        $journal->setCourse($course);
        
        $request = $this->getRequest();
        
        $form    = $this->createForm(new JournalType(), $journal);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em->persist($journal);
            $em->flush();

            return $this->redirect($this->generateUrl('journal', array('set' => 0, 'courseid'=> $courseid,)));
            
        }

        return array(
            'journal' => $journal,
            'form'   => $form->createView(),
            'roll' => $roll, 
            'role' => $role
        );
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{courseid}/{id}/edit", name="journal_edit")
     * @Template()
     */
    public function editAction($courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $role = $this->getCourseRole();
        $user = $this->getUser();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);

        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }
        elseif($user != $journal->getUser()){
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new JournalType(), $journal);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'journal'      => $journal,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'roll' => $roll, 
            'role' => $role
        );
    }

    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{courseid}/{id}/update", name="journal_update")
     * @Method("post")
     * @Template("MarcaJournalBundle:Journal:edit.html.twig")
     */
    public function updateAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }

        $editForm   = $this->createForm(new JournalType(), $journal);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($journal);
            $em->flush();

            return $this->redirect($this->generateUrl('journal', array('courseid'=> $courseid,)));
        }

        return array(
            'journal'      => $journal,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'roll' => $roll, 
            'role' => $role
        );
    }

    /**
     * Deletes a Journal entity.
     *
     * @Route("/{courseid}/{id}/delete", name="journal_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $user = $this->getUser();
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);

            if (!$journal) {
                throw $this->createNotFoundException('Unable to find Journal entity.');
            }
            elseif($user != $journal->getUser()){
                throw new AccessDeniedException();
            }

            $em->remove($journal);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('journal', array('set' => 0, 'courseid'=> $courseid,)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
