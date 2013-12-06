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
     * @Route("/{courseid}/{page}/{userid}/{user}/list", name="journal_list", defaults={"page" = 1,"userid" = 0,"user" = 0}))
     * @Template()
     */
    public function indexAction($courseid,$page,$user,$userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        if ($user==0){
            $user = $this->getUser();
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        }


        $course = $this->getCourse();
        $role = $this->getCourseRole();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        $journals = $em->getRepository('MarcaJournalBundle:Journal')->findJournalRecent($user, $course);
        
        //pagination
        $paginator = $this->get('knp_paginator');
        $journal = $paginator->paginate($journals,$this->get('request')->query->get('page', $page),1);
        
        return array('journal' => $journal, 'journals' => $journals, 'roll' => $roll, 'role' => $role, 'user' => $user, 'userid' => $userid);
    }


    /**
     * To use the autosave, new persists the entity and redirects to edit.
     *
     * @Route("/{courseid}/new", name="journal_new")
     * @Template()
     */
    public function newAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $em = $this->getEm();
        $user = $this->getUser();       
        $course = $this->getCourse();
        
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
     * @Template()
     */
    public function editAction($courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $role = $this->getCourseRole(); 
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

        $editForm = $this->createForm(new JournalType(), $journal);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'journal'      => $journal,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'roll' => $roll,
            'role' => $role,
            'user' => $user
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
        $user = $this->getUser();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        if (!$journal) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }

        $editForm   = $this->createForm(new JournalType(), $journal);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($journal);
            $em->flush();

            return $this->redirect($this->generateUrl('journal_list', array('courseid'=> $courseid,)));
        }

        return array(
            'journal'      => $journal,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'roll' => $roll,
            'role' => $role,
            'user' => $user
        );
    }
    
    
    /**
     * Saves via AJAX
     * @Route("/{courseid}/{id}/ajaxupdate", name="journal_ajax")
     * @Method("post")
     */
    public function ajaxUpdate($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);
        
        $request = $this->getRequest();
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
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($id);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->submit($request);

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

        return $this->redirect($this->generateUrl('journal_list', array('courseid'=> $courseid,)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
