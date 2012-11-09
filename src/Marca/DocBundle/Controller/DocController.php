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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $file = $doc->getFile();
        $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findAll();

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'file'        => $file,
            'markupsets' => $markupsets,
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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();

        $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findAll();
        
        $doc = new Doc();
        $doc->setBody('<p></p>');

        $form   = $this->createForm(new DocType(), $doc);

        return array(
            'doc' => $doc,
            'markupsets'      => $markupsets,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Doc entity.
     *
     * @Route("/{courseid}/{resource}/create", name="doc_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:new.html.twig")
     */
    public function createAction($courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $em = $this->getEm();
        $tag = '0';

        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project = $em->getRepository('MarcaCourseBundle:Project')->findOneByCourse($courseid);
              
        $file = new File();
        $file->setName('New eDoc');
        $file->setUser($user);
        $file->setProject($project);
        $file->setCourse($course);
        
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

            return $this->redirect($this->generateUrl('file_edit', array('id' => $file->getId(),'courseid'=> $courseid,'resource'=> $resource, 'tag' => $tag)));
            
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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findAll();
        
        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $editForm = $this->createForm(new DocType(), $doc);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'markupsets'      => $markupsets,
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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }
        
        $autosaveDoc = $doc->getAutosaveDoc();
        if ($autosaveDoc){
            $autosaveId = $autosaveDoc->getFile()->getId();
            $em->remove($autosaveDoc);
            $em->getRepository('MarcaFileBundle:File')->deleteEdoc($autosaveId);
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
     * Saves via AJAX
     * @Route("/{courseid}/{id}/ajaxupdate", name="doc_ajax")
     * @Method("post")
     */
    public function ajaxUpdate($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        
        $request = $this->get('request');
        $test = $request->request->get('test');
        
        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }
        
        $file = $doc->getFile();
        
        $autosaveDoc = $doc->getAutosaveDoc();
        
        if (!$autosaveDoc){
            $autosaveDoc = new Doc();
            $autosaveFile = new File();
            $autosaveFile->setUser($file->getUser());
            $autosaveFile->setCourse($file->getCourse());
            $autosaveFile->setProject($file->getProject());
            $currentTitle = $file->getName();
            $autosaveFile->setName($currentTitle."_backup");
            $autosaveDoc->setFile($autosaveFile);
            $autosaveDoc->setBody($test);
            
            $em->persist($autosaveFile);
            $em->persist($autosaveDoc);
        
            $doc->setAutosaveDoc($autosaveDoc);
        
            $em->persist($doc);
        }
        else{
            $autosaveDoc->setBody($test);
            $em->persist($autosaveDoc);
            $em->persist($doc);
        }
        
        $em->flush();
        
        $return = "success"; 
        return new Response($return,200,array('Content-Type'=>'application/json'));
    }


    /**
     * Deletes a Doc entity.
     *
     * @Route("/{courseid}/{id}/delete", name="doc_delete")
     * @Method("post")
     */
    public function deleteAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
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
  

}
