<?php

namespace Marca\ForumBundle\Controller;

use Marca\ForumBundle\Entity\Comment;
use Marca\ForumBundle\Form\CommentType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{

    /**
     * Displays a form to create a new Comment entity.
     *
     * @Route("/{courseid}/{forumid}/{parentid}/new", name="comment_new")
     */
    public function newAction(Request $request, $courseid,$forumid,$parentid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $parent = $em->getRepository('MarcaForumBundle:Forum')->find($parentid);        
        $comment = new Comment();
        $comment->setBody('<p></p>');        
        $form = $this->createCreateForm($comment, $courseid, $forumid, $parentid);

        return $this->render('MarcaForumBundle:Comment:new.html.twig', array(
            'forumid' => $forumid,
            'parentid' => $parentid,
            'parent' => $parent,
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }


    /**
     * Creates a form to create a Comment entity.
     *
     * @param Comment $comment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comment $comment, $courseid, $forumid, $parentid)
    {
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_create', ['courseid' => $courseid, 'forumid' => $forumid, 'parentid' => $parentid])]);
        return $form;
    }


    /**
     * Creates a new Comment entity.
     *
     * @Route("/{courseid}/{forumid}/{parentid}/create", name="comment_create")
     * @Method("post")
     * @Template("MarcaForumBundle:Comment:new.html.twig")
     */
    public function createAction(Request $request, $courseid,$forumid,$parentid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $parent = $em->getRepository('MarcaForumBundle:Forum')->find($parentid);   
        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($forumid);
        $comment  = new Comment();
        $user = $this->getUser();     
        $comment->setUser($user);
        $comment->setForum($forum);     
        
        $form = $this->createCreateForm($comment, $courseid, $forumid, $parentid);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $forumid)));
            
        }

        return $this->render('MarcaForumBundle:Comment:new.html.twig', array(
            'forumid' => $forumid,
            'parentid' => $parentid,
            'parent' => $parent,
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/{courseid}/{parentid}/{id}/edit", name="comment_edit")
     */
    public function editAction(Request $request, $courseid, $id, $parentid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $parent = $em->getRepository('MarcaForumBundle:Forum')->find($parentid);   
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }
        elseif ($user != $comment->getUser()) {
            throw new AccessDeniedException();
        }        

        $editForm = $this->createEditForm($comment, $courseid, $id, $parentid);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        return $this->render('MarcaForumBundle:Comment:edit.html.twig', array(
            'comment'      => $comment,
            'parent' => $parent,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Creates a form to edit a Comment entity.
     *
     * @param Comment $comment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Comment $comment, $courseid, $forumid, $parentid)
    {
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_update', ['id' => $comment->getId(), 'courseid' => $courseid, 'forumid' => $forumid, 'parentid' => $parentid])]);
        return $form;
    }

    /**
     * Edits an existing Comment entity.
     *
     * @Route("/{courseid}/{parentid}/{id}/update", name="comment_update")
     * @Method("post")
     */
    public function updateAction(Request $request, $id,$courseid,$parentid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $parent = $em->getRepository('MarcaForumBundle:Forum')->find($parentid);   
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $editForm = $this->createEditForm($comment, $courseid, $id, $parentid);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $comment->getForum()->getId(),)));
        }

        return $this->render('MarcaForumBundle:Comment:edit.html.twig', array(
            'comment'      => $comment,
            'parent' => $parent,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{courseid}/{id}/delete", name="comment_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $form = $this->createDeleteForm($id, $courseid);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $comment = $em->getRepository('MarcaForumBundle:Comment')->find($id);
            $forumid = $comment->getForum()->getId();

            if (!$comment) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            $em->remove($comment);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $forumid,)));
    }

    /**
     * Creates a form to delete a Comment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $id,'courseid' => $courseid,)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
