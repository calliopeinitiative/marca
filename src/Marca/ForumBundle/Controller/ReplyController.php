<?php

namespace Marca\ForumBundle\Controller;

use Marca\ForumBundle\Entity\Reply;
use Marca\ForumBundle\Form\ReplyType;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    public function newAction(Request $request, $commentid, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
       
        $em = $this->getEm();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid);        
        $reply = new Reply();
        $reply->setBody('<p></p>');
        $form = $this->createCreateForm($reply, $commentid, $courseid);
        

        return $this->render('MarcaForumBundle:Reply:new.html.twig', array(
            'commentid' => $commentid,
            'comment' => $comment,
            'reply' => $reply,
            'form'   => $form->createView()
        ));
    }


    /**
     * Creates a form to create a Reply entity.
     *
     * @param Reply $reply entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Reply $reply, $commentid, $courseid)
    {
        $form = $this->createForm(new ReplyType(), $reply, array(
            'action' => $this->generateUrl('reply_create', array('courseid' => $courseid, 'commentid' => $commentid)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }

    /**
     * Creates a new Reply entity.
     *
     * @Route("/{courseid}/{commentid}/create", name="reply_create")
     * @Method("post")
     */
    public function createAction(Request $request, $commentid,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid);
        $forum = $comment->getForum();
        $reply  = new Reply();
        $user = $this->getUser();    
        $reply->setUser($user);
        $reply->setComment($comment);
        
        $request = $this->getRequest();
        $form = $this->createCreateForm($reply, $commentid, $courseid);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($reply);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $forum->getId())));
            
        }

        return $this->render('MarcaForumBundle:Reply:new.html.twig', array(
            'commentid' => $commentid,
            'comment' => $comment,
            'reply' => $reply,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Reply entity.
     *
     * @Route("/{courseid}/{commentid}/{id}/edit", name="reply_edit")
     */
    public function editAction(Request $request, $id,$commentid,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid); 
        $user = $this->getUser();

        $reply = $em->getRepository('MarcaForumBundle:Reply')->find($id);

        if (!$reply) {
            throw $this->createNotFoundException('Unable to find Reply entity.');
        }
        elseif ($user != $reply->getUser()) {
            throw new AccessDeniedException();
        }  

        $editForm = $this->createEditForm($reply, $commentid, $courseid);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        return $this->render('MarcaForumBundle:Reply:edit.html.twig', array(
            'reply'      => $reply,
            'comment' => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Reply entity.
     *
     * @param Reply $reply entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Reply $reply, $commentid, $courseid)
    {
        $form = $this->createForm(ReplyType::class, $reply, [
            'action' => $this->generateUrl('reply_update', ['id' => $reply->getId(), 'courseid' => $courseid])]);
        return $form;
    }

    /**
     * Edits an existing Reply entity.
     *
     * @Route("/{courseid}/{commentid}/{id}/update", name="reply_update")
     * @Method("post")
     */
    public function updateAction(Request $request, $id,$commentid,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $comment = $em->getRepository('MarcaForumBundle:Comment')->find($commentid); 
        $reply = $em->getRepository('MarcaForumBundle:Reply')->find($id);

        if (!$reply) {
            throw $this->createNotFoundException('Unable to find Reply entity.');
        }

        $editForm = $this->createEditForm($reply, $commentid, $courseid);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($reply);
            $em->flush();

            return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $reply->getComment()->getForum()->getId(),)));
        }

        return $this->render('MarcaForumBundle:Reply:edit.html.twig', array(
            'reply'      => $reply,
            'comment' => $comment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Reply entity.
     *
     * @Route("/{courseid}/{id}/delete", name="reply_delete")
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
            $reply = $em->getRepository('MarcaForumBundle:Reply')->find($id);

            if (!$reply) {
                throw $this->createNotFoundException('Unable to find Reply entity.');
            }

            $em->remove($reply);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('forum_show', array('courseid' => $courseid,'id' => $reply->getComment()->getForum()->getId(),)));
    }

    /**
     * Creates a form to delete a Reply entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reply_delete', array('id' => $id,'courseid' => $courseid,)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
