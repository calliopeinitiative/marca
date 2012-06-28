<?php

namespace Marca\DocBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response; 
use Marca\DocBundle\Entity\Doc;
use Marca\DocBundle\Entity\Autosave;
use Marca\DocBundle\Form\DocType;
use Marca\FileBundle\Entity\File;
use Marca\CourseBundle\Entity\Project;

/**
 * Doc controller.
 *
 * @Route("/doc")
 */
class DocController extends Controller
{
    /**
     * Lists all Doc entities.
     *
     * @Route("/{courseid}/", name="doc")
     * @Template()
     */
    public function indexAction()
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        $em = $this->getEm();

        $docs = $em->getRepository('MarcaDocBundle:Doc')->findAll();

        return array('docs' => $docs);
    }

    /**
     * Finds and displays a Doc entity.
     *
     * @Route("/{courseid}/{id}/show", name="doc_show")
     * @Template()
     */
    public function showAction($id)
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $file = $doc->getFile();

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'file'        => $file,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Doc entity.
     *
     * @Route("/{courseid}/new", name="doc_new")
     * @Template()
     */
    public function newAction()
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByUser($user);
        
        $doc = new Doc();
        $doc->setBody('<p></p>');

        $form   = $this->createForm(new DocType(), $doc);

        return array(
            'doc' => $doc,
            'markup'      => $markup,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Doc entity.
     *
     * @Route("/{courseid}/create", name="doc_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:new.html.twig")
     */
    public function createAction($courseid)
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $courseid = $this->get('request')->getSession()->get('courseid');
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project = $em->getRepository('MarcaCourseBundle:Project')->findOneByCourse($courseid);
              
        $file = new File();
        $file->setName('New eDoc');
        $file->setUser($user);
        $file->setProject($project);
        $file->setCourse($course);
        $file->setPath('doc');
        
        $doc  = new Doc();    
        $doc->setFile($file); 
        $request = $this->getRequest();
        $form    = $this->createForm(new DocType(), $doc);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($file);
            $em->persist($doc);
            $em->flush();

            return $this->redirect($this->generateUrl('file_edit', array('id' => $file->getId(),'courseid'=> $courseid,)));
            
        }

        return array(
            'doc' => $doc,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Doc entity.
     *
     * @Route("/{courseid}/{id}/edit", name="doc_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByUser($user);
        
        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $editForm = $this->createForm(new DocType(), $doc);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'markup'      => $markup,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Doc entity.
     *
     * @Route("/{courseid}/{id}/update", name="doc_update")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:edit.html.twig")
     */
    public function updateAction($id,$courseid)
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $editForm   = $this->createForm(new DocType(), $doc);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($doc);
            $em->flush();

            return $this->redirect($this->generateUrl('doc_show', array('id' => $id, 'courseid'=> $courseid,)));
        }

        return array(
            'doc'      => $doc,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Doc entity.
     *
     * @Route("/{courseid}/{id}/delete", name="doc_delete")
     * @Method("post")
     */
    public function deleteAction($id,$courseid)
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);

            if (!$doc) {
                throw $this->createNotFoundException('Unable to find Doc entity.');
            }

            $em->remove($doc);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('doc', array('courseid'=> $courseid,)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }


    /**
     * Doc Autosave
     *
     * @Route("/{courseid}/autosave", name="doc_autosave")
     * 
     */
    public function autosaveAction()
    {
        $allowed = array("instructor", "student");
        $this->restrictAccessTo($allowed);
        
        $autosave = new Autosave();
        $autosave->debugMode = true;
        $temp_file = '/var/www/marca/src/Marca/uploads/autosave_'.time().'.txt';
        $autosave->saveToFile($temp_file);
        return Response(); 
    }  
    

}
