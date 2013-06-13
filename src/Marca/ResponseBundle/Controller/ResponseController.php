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
     * Lists all Response entities.
     *
     * @Route("/{courseid}/", name="response")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $responses = $em->getRepository('MarcaResponseBundle:Response')->findAll();

        return array('responses' => $responses);
    }

    /**
     * Finds and displays a Response response.
     *
     * @Route("/{courseid}/{id}/show", name="response_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

        if (!$response) {
            throw $this->createNotFoundException('Unable to find Response response.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'response'      => $response,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{view}/{page}/new", name="response_new", defaults={"page" = 1})
     * @Template()
     */
    public function newAction($source, $sourceid, $page)
    {
        $response = new Response();
        $response->setBody('<p></p>');
        $form   = $this->createForm(new ResponseType(), $response);

        if ($source == 'journal')
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
     * @Route("/{courseid}/{source}/{sourceid}/{view}/{page}/create", name="response_create", defaults={"page" = 1})
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:new.html.twig")
     */
    public function createAction($courseid, $source, $sourceid, $view, $page)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $response  = new Response();
        $response->setUser($user);
        $response->setBody('<p></p>');
        
        if ($source == 'journal')
        {
            $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);
            $response->setJournal($journal);
        }
            else
        {
            $journal = '';     
            $doc = $em->getRepository('MarcaDocBundle:Doc')->find($sourceid);
            $file = $doc->getFile();
            $response->setFile($file); 
            
        };
        $request = $this->getRequest();
        $form    = $this->createForm(new ResponseType(), $response);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($response);
            $em->flush();

            return $this->redirect($this->generateUrl($source, array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view, 'page' => $page)));
            
        }

        return array(
            'response' => $response,
            'journal' => $journal,
            'source' => $source,
            'sourceid' => $sourceid,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/{page}/edit", name="response_edit", defaults={"page" = 1})
     * @Template()
     */
    public function editAction($id, $source, $sourceid, $view, $page)
    {
        $em = $this->getEm();
        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);
        
        
        if ($source == 'journal')
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
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/{page}/update", name="response_update", defaults={"page" = 1})
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:edit.html.twig")
     */
    public function updateAction($id, $source, $sourceid, $courseid, $view, $page)
    {
        $em = $this->getEm();
        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

        
        if ($source == 'journal')
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

        $editForm   = $this->createForm(new ResponseType(), $response);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($response);
            $em->flush();

            return $this->redirect($this->generateUrl($source, array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view, 'page' => $page)));
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

    /**
     * Deletes a Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/delete", name="response_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid, $source, $sourceid, $view)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

            if (!$response) {
                throw $this->createNotFoundException('Unable to find Response response.');
            }

            $em->remove($response);
            $em->flush();
        }

        return $this->redirect($this->generateUrl($source, array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
