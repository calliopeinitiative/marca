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
     * Create Sidebar fragment
     *
     * @Route("/{courseid}/sidebar", name="forum_sidebar")
     */
    public function createSidebarAction($courseid)
    {
        return $this->render('MarcaForumBundle::sidebar.html.twig', array( ));
    }


    /**
     * Lists all Forum entities.
     *
     * @Route("/{courseid}/page", name="forum")
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
        $forumEntries = $paginator->paginate($forumEntries,$this->get('request')->query->get('page', 1),10);
        
        return $this->render('MarcaForumBundle:Forum:index.html.twig', array(
            'forumEntries' => $forumEntries
        ));
    }

    /**
     * Finds and displays a Forum entity.
     *
     * @Route("/{courseid}/{id}/show", name="forum_show")
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

        return $this->render('MarcaForumBundle:Forum:show.html.twig', array(
            'forum'=> $forum
        ));
    }

    /**
     * Displays a form to create a new Forum entity.
     *
     * @Route("/{courseid}/new", name="forum_new")
     */
    public function newAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $newForum = new Forum();
        $newForum->setBody('<p></p>');
        $form   = $this->createForm(new ForumType(), $newForum);

        return $this->render('MarcaForumBundle:Forum:new.html.twig', array(
            'newForum' => $newForum,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Forum entity.
     *
     * @Route("/{courseid}/create", name="forum_create")
     * @Method("post")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $user = $this->getUser();
        $course = $this->getCourse();
        $newForum  = new Forum();
        $newForum->setUser($user);
        $newForum->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new ForumType(), $newForum);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($newForum);
            $em->flush();

            return $this->redirect($this->generateUrl('forum', array('courseid' => $courseid, 'set' => 0)));
            
        }

        return $this->render('MarcaForumBundle:Forum:new.html.twig', array(
            'newForum' => $newForum,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Forum entity.
     *
     * @Route("/{courseid}/{id}/edit", name="forum_edit")
     */
    public function editAction($id, $courseid)
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

        $options = array();
        $editForm = $this->createEditForm($forum, $courseid, $options);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        return $this->render('MarcaForumBundle:Forum:edit.html.twig', array(
            'forum'      => $forum,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Journal entity.
     *
     * @param Forum $forum
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Forum $forum, $courseid, $options)
    {
        $form = $this->createForm(new ForumType($options), $forum, array(
            'action' => $this->generateUrl('forum_update', array('id' => $forum->getId(),'courseid' => $courseid,)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }

    /**
     * Edits an existing Forum entity.
     *
     * @Route("/{courseid}/{id}/update", name="forum_update")
     * @Method("post")
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
        $options = array();
        $editForm = $this->createEditForm($forum, $courseid, $options);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($forum);
            $em->flush();

            return $this->redirect($this->generateUrl('forum', array('courseid' => $courseid, 'set' => 0)));
        }

        return $this->render('MarcaForumBundle:Forum:edit.html.twig', array(
            'forum'      => $forum,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
        
        $form = $this->createDeleteForm($id, $courseid);
        $request = $this->getRequest();

        $form->handleRequest($request);

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

    /**
     * Creates a form to delete a Forum entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('forum_delete', array('id' => $id,'courseid' => $courseid,)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
