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
     * @Route("/{courseid}/{source}/{sourceid}/{view}/new", name="response_new")
     * @Template()
     */
    public function newAction($source, $sourceid)
    {
        $response = new Response();
        $response->setBody('<p></p>');
        $form   = $this->createForm(new ResponseType(), $response);

        return array(
            'response' => $response,
            'source' => $source,
            'sourceid' => $sourceid,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{view}/create", name="response_create")
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:new.html.twig")
     */
    public function createAction($courseid, $source, $sourceid, $view)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $response  = new Response();
        $response->setUser($user);
        
        if ($source == 'journal')
        {
            $journal = $em->getRepository('MarcaJournalBundle:Journal')->find($sourceid);
            $response->setJournal($journal);
        }
            else
        {
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

            return $this->redirect($this->generateUrl($source, array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view)));
            
        }

        return array(
            'response' => $response,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/edit", name="response_edit")
     * @Template()
     */
    public function editAction($id, $source, $sourceid, $view)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

        if (!$response) {
            throw $this->createNotFoundException('Unable to find Response response.');
        }

        $editForm = $this->createForm(new ResponseType(), $response);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'response'      => $response,
            'source' => $source,
            'sourceid' => $sourceid,
            'view' => $view,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Response response.
     *
     * @Route("/{courseid}/{source}/{sourceid}/{id}/{view}/update", name="response_update")
     * @Method("post")
     * @Template("MarcaResponseBundle:Response:edit.html.twig")
     */
    public function updateAction($id, $source, $sourceid, $courseid, $view)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $response = $em->getRepository('MarcaResponseBundle:Response')->find($id);

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

            return $this->redirect($this->generateUrl($source, array('courseid' => $courseid, 'id' => $sourceid, 'view' => $view)));
        }

        return array(
            'response'      => $response,
            'source' => $source,
            'sourceid' => $sourceid,
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
