<?php

namespace Marca\ForumBundle\Controller;

use Marca\ForumBundle\Entity\Forum;
use Marca\ForumBundle\Form\ForumType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    public function createSidebarAction()
    {
        return $this->render('MarcaForumBundle::sidebar.html.twig', array( ));
    }


    /**
     * Lists all Forum entities.
     *
     * @Route("/{courseid}/page", name="forum")
     */
    public function indexAction(Request $request)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse($request);
        $forumEntries = $em->getRepository('MarcaForumBundle:Forum')->findForumByCourse($course);
        
        //pagination
        $paginator = $this->get('knp_paginator');
        $forumEntries = $paginator->paginate($forumEntries,$request->query->get('page', 1),10);
        
        return $this->render('MarcaForumBundle:Forum:index.html.twig', array(
            'forumEntries' => $forumEntries
        ));
    }

    /**
     * Finds and displays a Forum entity.
     *
     * @Route("/{courseid}/{id}/show", name="forum_show")
     */
    public function showAction(Request $request, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
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
    public function newAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $forum = new Forum();
        $forum->setBody('<p></p>');
        $form = $this->createCreateForm($forum, $courseid);

        return $this->render('MarcaForumBundle:Forum:new.html.twig', array(
            '$forum' => $forum,
            'form'   => $form->createView()
        ));
    }


    /**
     * Creates a form to create a Forum entity.
     *
     * @param Forum $forum entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Forum $forum, $courseid)
    {
        $form = $this->createForm(new ForumType(), $forum, array(
            'action' => $this->generateUrl('forum_create', array('courseid' => $courseid)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }

    /**
     * Creates a new Forum entity.
     *
     * @Route("/{courseid}/create", name="forum_create")
     * @Method("post")
     */
    public function createAction(Request $request, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $user = $this->getUser();
        $course = $this->getCourse($request);
        $forum  = new Forum();
        $forum->setUser($user);
        $forum->setCourse($course);
        $form = $this->createCreateForm($forum, $courseid);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($forum);
            $em->flush();

            return $this->redirect($this->generateUrl('forum', array('courseid' => $courseid, 'set' => 0)));
            
        }

        return $this->render('MarcaForumBundle:Forum:new.html.twig', array(
            'forum' => $forum,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Forum entity.
     *
     * @Route("/{courseid}/{id}/edit", name="forum_edit")
     */
    public function editAction(Request $request, $id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
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
     * Creates a form to edit a Forum entity.
     *
     * @param Forum $forum entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Forum $forum, $courseid)
    {
        $form = $this->createForm(new ForumType(), $forum, array(
            'action' => $this->generateUrl('forum_update', array('id' => $forum->getId(),'courseid' => $courseid)),
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
    public function updateAction(Request $request, $courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$forum) {
            throw $this->createNotFoundException('Unable to find Forum.');
        }

        $editForm = $this->createEditForm($forum, $courseid);
        $deleteForm = $this->createDeleteForm($id, $courseid);

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
    public function deleteAction(Request $request, $courseid, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        $user = $this->getUser();
        
        $form = $this->createDeleteForm($id, $courseid);

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
