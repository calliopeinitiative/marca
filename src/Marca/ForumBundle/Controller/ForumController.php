<?php

namespace Marca\ForumBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Marca\ForumBundle\Entity\Forum;
use Marca\ForumBundle\Form\ForumType;

/**
 * Forum controller.
 *
 * @Route("/forum")
 */
class ForumController extends Controller
{
    /**
     * Lists all Forum entities.
     *
     * @Route("/{courseid}/page", name="forum")
     * @Template()
     */
    public function indexAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $forumEntries = $em->getRepository('MarcaForumBundle:Forum')->findForumByCourse($course);
        
        //pagination
        $paginator = $this->get('knp_paginator');
        $forumEntries = $paginator->paginate($forumEntries,$this->get('request')->query->get('page', 1),5);
        
        return array('forumEntries' => $forumEntries);
    }

    /**
     * Finds and displays a Forum entity.
     *
     * @Route("/{courseid}/{id}/show", name="forum_show")
     * @Template()
     */
    public function showAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $forum = $em->getRepository('MarcaForumBundle:Forum')->findForumDesc($id);

        if (!$forum) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array('forum'      => $forum,);
    }

    /**
     * Displays a form to create a new Forum entity.
     *
     * @Route("/{courseid}/new", name="forum_new")
     * @Template()
     */
    public function newAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $newForum = new Forum();
        $newForum->setBody('<p></p>');
        $form   = $this->createForm(new ForumType(), $newForum);

        return array(
            'newForum' => $newForum,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Forum entity.
     *
     * @Route("/{courseid}/create", name="forum_create")
     * @Method("post")
     * @Template("MarcaForumBundle:Forum:new.html.twig")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $newForum  = new Forum();
        $newForum->setUser($user);
        $newForum->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new ForumType(), $newForum);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($newForum);
            $em->flush();

            return $this->redirect($this->generateUrl('forum', array('courseid' => $courseid, 'set' => 0)));
            
        }

        return array(
            'newForum' => $newForum,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Forum entity.
     *
     * @Route("/{courseid}/{id}/edit", name="forum_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        
        $em = $this->getEm();

        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$forum) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }
        elseif($user != $forum->getUser()){
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new ForumType(), $forum);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'forum'      => $forum,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Forum entity.
     *
     * @Route("/{courseid}/{id}/update", name="forum_update")
     * @Method("post")
     * @Template("MarcaForumBundle:Forum:edit.html.twig")
     */
    public function updateAction($courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$forum) {
            throw $this->createNotFoundException('Unable to find Forum.');
        }

        $editForm   = $this->createForm(new ForumType(), $forum);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($forum);
            $em->flush();

            return $this->redirect($this->generateUrl('forum', array('courseid' => $courseid, 'set' => 0)));
        }

        return array(
            'forum'      => $forum,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Forum entity.
     *
     * @Route("/{courseid}/{id}/delete", name="forum_delete")
     * @Method("post")
     */
    public function deleteAction($courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $forum = $em->getRepository('MarcaForumBundle:Forum')->find($id);

            if (!$forum) {
                throw $this->createNotFoundException('Unable to find Forum entity.');
            }
            elseif($user != $forum->getUser()){
                throw new AccessDeniedException();
            }

            $em->remove($forum);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('forum', array('courseid' => $courseid, 'set' => 0)));
    }

    private function createDeleteForm($id)
    {
        //currently unused!
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
