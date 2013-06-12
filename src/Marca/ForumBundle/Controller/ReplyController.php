<?php

namespace Marca\ForumBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Marca\ForumBundle\Entity\Reply;
use Marca\ForumBundle\Form\ReplyType;

/**
 * Reply controller.
 *
 * @Route("/reply")
 */
class ReplyController extends Controller
{

    /**
     * Displays a form to create a new Reply entity.
     *
     * @Route("/{courseid}/{commentid}/new", name="reply_new")
     * @Template()
     */
    public function newAction($commentid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
       
        $em = $this->getEm();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid);        
        $reply = new Reply();
        $reply->setBody('<p></p>');
        $form   = $this->createForm(new ReplyType(), $reply);
        

        return array(
            'commentid' => $commentid,
            'comment' => $comment,
            'reply' => $reply,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Reply entity.
     *
     * @Route("/{courseid}/{commentid}/create", name="reply_create")
     * @Method("post")
     * @Template("MarcaForumBundle:Reply:new.html.twig")
     */
    public function createAction($commentid,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid);
        $forumid = $comment->getForum()->getid();
        $reply  = new Reply();
        $user = $this->getUser();    
        $reply->setUser($user);
        $reply->setComment($comment);
        
        $request = $this->getRequest();
        $form    = $this->createForm(new ReplyType(), $reply);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($reply);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $forumid)));
            
        }

        return array(
            'commentid' => $commentid,
            'comment' => $comment,
            'reply' => $reply,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Reply entity.
     *
     * @Route("/{courseid}/{commentid}/{id}/edit", name="reply_edit")
     * @Template()
     */
    public function editAction($id,$commentid,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getDoctrine()->getEntityManager();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid); 
        $user = $this->getUser();

        $reply = $em->getRepository('MarcaForumBundle:Reply')->find($id);

        if (!$reply) {
            throw $this->createNotFoundException('Unable to find Reply entity.');
        }
        elseif ($user != $reply->getUser()) {
            throw new AccessDeniedException();
        }  

        $editForm = $this->createForm(new ReplyType(), $reply);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'reply'      => $reply,
            'comment' => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Reply entity.
     *
     * @Route("/{courseid}/{commentid}/{id}/update", name="reply_update")
     * @Method("post")
     * @Template("MarcaForumBundle:Reply:edit.html.twig")
     */
    public function updateAction($id,$commentid,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getDoctrine()->getEntityManager();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid); 
        $reply = $em->getRepository('MarcaForumBundle:Reply')->find($id);

        if (!$reply) {
            throw $this->createNotFoundException('Unable to find Reply entity.');
        }

        $editForm   = $this->createForm(new ReplyType(), $reply);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($reply);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $reply->getComment()->getForum()->getId(),)));
        }

        return array(
            'reply'      => $reply,
            'comment' => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Reply entity.
     *
     * @Route("/{courseid}/{id}/delete", name="reply_delete")
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
            $reply = $em->getRepository('MarcaForumBundle:Reply')->find($id);

            if (!$reply) {
                throw $this->createNotFoundException('Unable to find Reply entity.');
            }

            $em->remove($reply);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reply'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
