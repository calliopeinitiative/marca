<?php

namespace Marca\ResponseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\ResponseBundle\Entity\Response;
use Marca\ResponseBundle\Form\ResponseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Response controller.
 *
 * @Route("/response")
 */
class ResponseController extends Controller
{
    /**
     * Displays a form to create a new Response response.
     *
     * @Route("/{courseid}/{sourceid}/{page}/{user}/{userid}/new", name="response_new", defaults={"page" = 1,"user" = 1})
     * @Template()
     */
    public function newAction(Request $request, $courseid, $sourceid, $page, $user, $userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $response = new Response();
        $response->setBody('<p></p>');
        $options = array();
        $form = $this->createCreateForm($response, $courseid, $sourceid, $options, $page, $user, $userid);

        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);

        return array(
            'response' => $response,
            'journal' => $journal,
            'sourceid' => $sourceid,
            'form'   => $form->createView()
        );
    }


    /**
     * Creates a form to create a Grade entity.
     *
     * @param Response $response The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Response $response, $courseid, $sourceid, $options, $page, $user, $userid)
    {
        $form = $this->createForm(new ResponseType($options), $response, array(
            'action' => $this->generateUrl('response_create', array('courseid' => $courseid, 'sourceid' => $sourceid,'page' => $page,'user' => $user,'userid' => $userid, )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }


    /**
     * Creates a new Response response.
     *
     * @Route("/{courseid}/{sourceid}/{page}/{user}/create", name="response_create", defaults={"page" = 1,"user" = 1})
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:new.html.twig")
     */
    public function createAction(Request $request, $courseid, $sourceid, $page, $user)
    {
        
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $journal_list = $user;
        $user = $this->getUser();
        $response  = new Response();
        $response->setUser($user);

        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);
        $userid = $journal->getUser()->getId();
        $response->setJournal($journal);
        $request = $this->getRequest();
        $options = array();
        $form = $this->createCreateForm($response, $courseid, $sourceid, $options, $page, $user, $userid);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($response);
            $em->flush();

            return $this->redirect($this->generateUrl('journal_list', array('courseid' => $courseid, 'page' => $page,'userid'=>$userid, 'user'=>$journal_list)));   
            }

        return array(
            'response' => $response,
            'journal' => $journal,
            'sourceid' => $sourceid,
            'form'   => $form->createView()
            );
        }

    /**
     * Displays a form to edit an existing Response response.
     *
     * @Route("/{courseid}/{sourceid}/{id}/{page}/{user}/{userid}/edit", name="response_edit", defaults={"page" = 1,"user" = 1})
     * @Template()
     */
    public function editAction(Request $request, $id, $courseid, $sourceid, $page, $user, $userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);


        if (!$response) {
            throw $this->createNotFoundException('Unable to find Response response.');
        }

        $options = array();
        $editForm = $this->createEditForm($response, $courseid, $sourceid, $options, $page, $user, $userid);
        $deleteForm = $this->createDeleteForm($id, $courseid, $page, $user, $userid);

        return array(
            'response'      => $response,
            'sourceid' => $sourceid,
            'journal' => $journal,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Creates a form to edit a Response entity.
     *
     * @param Response $response
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Response $response, $courseid, $sourceid, $options, $page, $user, $userid)
    {
        $form = $this->createForm(new ResponseType($options), $response, array(
            'action' => $this->generateUrl('response_update', array('id' => $response->getId(),'courseid' => $courseid, 'sourceid' => $sourceid,'page' => $page,'user' => $user,'userid' => $userid, )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }



    /**
     * Edits an existing Response response.
     *
     * @Route("/{courseid}/{sourceid}/{id}/{page}/{user}/update", name="response_update", defaults={"page" = 1,"user" = 1})
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $sourceid, $courseid, $page, $user)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $em = $this->getEm();
        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

        if (!$response) {
            throw $this->createNotFoundException('Unable to find Response response.');
        }

             $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);          
             $journal_list = $journal->getUser();
             $userid = $journal_list->getId();
             $response->setJournal($journal);

             $options = array();
             $editForm = $this->createEditForm($response, $courseid, $sourceid, $options, $page, $user, $userid);
             $deleteForm = $this->createDeleteForm($id, $courseid,  $page, $user, $userid);

             $request = $this->getRequest();
             $editForm->handleRequest($request);

            if ($editForm->isValid()) {
            $em->persist($response);
            $em->flush();

            return $this->redirect($this->generateUrl('journal_list', array('courseid' => $courseid, 'page' => $page,'userid'=>$userid, 'user'=>$user)));   
            }

            return array(
            'response'      => $response,
            'sourceid' => $sourceid,
            'journal' => $journal,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Response response.
     *
     * @Route("/{courseid}/{id}/{page}/{user}/{userid}/delete", name="response_delete")
     * @Method("post")
     */
    public function deleteAction(Request $request, $id, $courseid, $page, $user, $userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        
        $form = $this->createDeleteForm($id, $courseid, $page, $user, $userid);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

            if (!$response) {
                throw $this->createNotFoundException('Unable to find Response response.');
            }

            $em->remove($response);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('journal_list', array('courseid' => $courseid, 'page' => $page,'userid'=>$userid, 'user'=>$user)));
    }


    /**
     * Creates a form to delete a Journal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid, $page, $user, $userid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('response_delete', array('id' => $id,'courseid' => $courseid, 'page' => $page,'user' => $user,'userid' => $userid,)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }


}
