<?php

namespace Marca\ForumBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Route("/{courseid}/{set}/page", name="forum")
     * @Template()
     */
    public function indexAction($set)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $set = $set;
        $forumEntries = $em->getRepository('MarcaForumBundle:Forum')->findForumRecent($user, $set);
        return array('forumEntries' => $forumEntries,'set' => $set);
    }

    /**
     * Finds and displays a Forum entity.
     *
     * @Route("/{courseid}/{forumid}/show", name="forum_show")
     * @Template()
     */
    public function showAction($forumid)
    {
        $em = $this->getEm();

        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($forumid);

        if (!$forum) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $deleteForm = $this->createDeleteForm($forumid);

        return array(
            'forum'      => $forum,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Forum entity.
     *
     * @Route("/{courseid}/new", name="forum_new")
     * @Template()
     */
    public function newAction()
    {
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
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $newForum  = new Forum();
        $newForum->setUser($user);
        $newForum->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new ForumType(), $newForum);
        $form->bindRequest($request);

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
     * @Route("/{courseid}/{forumid}/edit", name="forum_edit")
     * @Template()
     */
    public function editAction($forumid)
    {
        $em = $this->getEm();

        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($forumid);

        if (!$forum) {
            throw $this->createNotFoundException('Unable to find Forum.');
        }

        $editForm = $this->createForm(new ForumType(), $forum);
        $deleteForm = $this->createDeleteForm($forumid);

        return array(
            'forum'      => $forum,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Forum entity.
     *
     * @Route("/{courseid}/{forumid}/update", name="forum_update")
     * @Method("post")
     * @Template("MarcaForumBundle:Forum:edit.html.twig")
     */
    public function updateAction($courseid, $forumid)
    {
        $em = $this->getEm();

        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($forumid);

        if (!$forum) {
            throw $this->createNotFoundException('Unable to find Forum.');
        }

        $editForm   = $this->createForm(new ForumType(), $forum);
        $deleteForm = $this->createDeleteForm($forumid);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

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
     * @Route("/{courseid}/{forumid}/delete", name="forum_delete")
     * @Method("post")
     */
    public function deleteAction($courseid, $forumid)
    {
        $form = $this->createDeleteForm($forumid);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $forum = $em->getRepository('MarcaForumBundle:Forum')->find($forumid);

            if (!$forum) {
                throw $this->createNotFoundException('Unable to find Forum.');
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