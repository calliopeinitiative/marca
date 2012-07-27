<?php

namespace Marca\ForumBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Marca\ForumBundle\Entity\Comment;
use Marca\ForumBundle\Form\CommentType;

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
     * @Template()
     */
    public function newAction($forumid,$parentid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $comment = new Comment();

        $comment->setBody('<p></p>');
        
        $form   = $this->createForm(new CommentType(), $comment);

        return array(
            'forumid' => $forumid,
            'comment' => $comment,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Comment entity.
     *
     * @Route("/{courseid}/{forumid}/create", name="comment_create")
     * @Method("post")
     * @Template("MarcaForumBundle:Comment:new.html.twig")
     */
    public function createAction($forumid,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $forum = $em->getRepository('MarcaForumBundle:Forum')->find($forumid);
        $comment  = new Comment();
        $user = $this->getUser();     
        $comment->setUser($user);
        $comment->setForum($forum);     
        
        $request = $this->getRequest();
        $form    = $this->createForm(new CommentType(), $comment);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $forumid)));
            
        }

        return array(
            'comment' => $comment,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/{courseid}/{id}/edit", name="comment_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();

        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }
        elseif ($user != $comment->getUser()) {
            throw new AccessDeniedException();
        }        

        $editForm = $this->createForm(new CommentType(), $comment);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'comment'      => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Comment entity.
     *
     * @Route("/{courseid}/{id}/update", name="comment_update")
     * @Method("post")
     * @Template("MarcaForumBundle:Comment:edit.html.twig")
     */
    public function updateAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getDoctrine()->getEntityManager();

        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $editForm   = $this->createForm(new CommentType(), $comment);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $comment->getForum()->getId(),)));
        }

        return array(
            'comment'      => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{courseid}/{id}/delete", name="comment_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $comment = $em->getRepository('MarcaForumBundle:Comment')->find($id);

            if (!$comment) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            $em->remove($comment);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comment'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
