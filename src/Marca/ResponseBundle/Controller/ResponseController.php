<?php

namespace Marca\ResponseBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\ResponseBundle\Entity\Response;
use Marca\ResponseBundle\Form\ResponseType;

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
     * @Route("/{courseid}/{source}/{sourceid}/{view}/{page}/{user}/{userid}/new", name="response_new", defaults={"page" = 1,"user" = 1})
     * @Template()
     */
    public function newAction($source, $sourceid, $page)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $response = new Response();
        $response->setBody('<p></p>');
        $form   = $this->createForm(new ResponseType(), $response);

        if ($source == 'journal_user')
        {
        $em = $this->getEm();
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);
        }
        else
        {
        $journal = '';           
        };        

        return array(
            'response' => $response,
            'source' => $source,
            'journal' => $journal,
            'sourceid' => $sourceid,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{view}/{page}/{user}/create", name="response_create", defaults={"page" = 1,"user" = 1})
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:new.html.twig")
     */
    public function createAction($courseid, $source, $sourceid, $view, $page, $user)
    {
        
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $journal_user = $user;
        $user = $this->getUser();
        $response  = new Response();
        $response->setUser($user);
        $response->setBody('<p></p>');
        
        if ($source == 'journal_user')
        {
            $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);
            $userid = $journal->getUser()->getId();
            $response->setJournal($journal);
            $request = $this->getRequest();
            $form    = $this->createForm(new ResponseType(), $response);
            $form->bind($request);

            if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($response);
            $em->flush();

            return $this->redirect($this->generateUrl('journal_user', array('courseid' => $courseid, 'page' => $page,'userid'=>$userid, 'user'=>$journal_user)));   
            }

            return array(
            'response' => $response,
            'journal' => $journal,
            'source' => $source,
            'sourceid' => $sourceid,
            'form'   => $form->createView()
            );
        }
            else
        {
            $file = $em->getRepository('MarcaFileBundle:File')->find($sourceid);
            $response->setFile($file);
            $request = $this->getRequest();
            $form    = $this->createForm(new ResponseType(), $response);
            $form->bind($request);

            if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($response);
            $em->flush();

            if ($file->getDoc())    {
                return $this->redirect($this->generateUrl('doc_show', array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view)));
            }
                else {
                    return $this->redirect($this->generateUrl('file_viewer', array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view)));
                }

            }

            return array(
            'response' => $response,
            'source' => $source,
            'sourceid' => $sourceid,
            'form'   => $form->createView()
            );
            
        };

    }

    /**
     * Displays a form to edit an existing Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/{page}/{user}/{userid}/edit", name="response_edit", defaults={"page" = 1,"user" = 1})
     * @Template()
     */
    public function editAction($id, $source, $sourceid, $view, $page)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);
        
        if ($source == 'journal_user')
        {    
        $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);
        }
        else
        {
        $journal = '';           
        }; 

        if (!$response) {
            throw $this->createNotFoundException('Unable to find Response response.');
        }

        $editForm = $this->createForm(new ResponseType(), $response);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'response'      => $response,
            'source' => $source,
            'sourceid' => $sourceid,
            'journal' => $journal,
            'view' => $view,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/{page}/{user}/update", name="response_update", defaults={"page" = 1,"user" = 1})
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:edit.html.twig")
     */
    public function updateAction($id, $source, $sourceid, $courseid, $view, $page, $user)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);
        if (!$response) {
            throw $this->createNotFoundException('Unable to find Response response.');
        }
        
        if ($source == 'journal_user')
        {
             $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);          
             $journal_user = $journal->getUser();
             $userid = $journal_user->getId();
             $response->setJournal($journal);
             $editForm   = $this->createForm(new ResponseType(), $response);
             $deleteForm = $this->createDeleteForm($id);
             $request = $this->getRequest();
             $editForm->bind($request);

            if ($editForm->isValid()) {
            $em->persist($response);
            $em->flush();

            return $this->redirect($this->generateUrl('journal_user', array('courseid' => $courseid, 'page' => $page,'userid'=>$userid, 'user'=>$user)));   
            }

            return array(
            'response'      => $response,
            'source' => $source,
            'sourceid' => $sourceid,
            'journal' => $journal,
            'view' => $view,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
        }
            else
        {
             $journal = '';     
             $editForm   = $this->createForm(new ResponseType(), $response);
             $deleteForm = $this->createDeleteForm($id);
             $request = $this->getRequest();
             $editForm->bind($request);

            if ($editForm->isValid()) {
            $em->persist($response);
            $em->flush();

            return $this->redirect($this->generateUrl('doc_show', array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view)));
            }

            return array(
            'response' => $response,
            'source' => $source,
            'journal' => $journal,    
            'sourceid' => $sourceid,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ); 
        };
    }

    /**
     * Deletes a Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/{page}/{user}/{userid}/delete", name="response_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid, $source, $sourceid, $view, $page,$user,$userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

            if (!$response) {
                throw $this->createNotFoundException('Unable to find Response response.');
            }

            $em->remove($response);
            $em->flush();
        }
        if ($source == 'journal_user')
        {
        return $this->redirect($this->generateUrl('journal_user', array('courseid' => $courseid, 'page' => $page,'userid'=>$userid, 'user'=>$user)));
        }
        else
        {
        return $this->redirect($this->generateUrl('doc_show', array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view)));    
        }
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
