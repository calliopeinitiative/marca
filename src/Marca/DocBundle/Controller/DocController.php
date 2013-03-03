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
     * @Route("/{courseid}/{id}/{view}/show", name="doc_show")
     * @Template()
     */
    public function showAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $text = $doc->getBody();
        $count = str_word_count($text);
        $file = $doc->getFile();
        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByCourse($course);

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'count'=> $count,
            'file'        => $file,
            'markup' => $markup,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Creates a new Doc entity.
     *
     * @Route("/{courseid}/{fileid}/{resource}/{view}/create", name="doc_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:new.html.twig")
     */
    public function createAction($courseid, $resource, $view, $fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $em = $this->getEm();
        $tag = '0';

        $course = $this->getCourse();
        $project = $em->getRepository('MarcaCourseBundle:Project')->findOneByCourse($courseid);
        $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);

              
        $file = new File();
        $file->setName('New Document');
        $file->setUser($user);
        $file->setProject($project);
        $file->setCourse($course);
        if ($fileid != 0) {
            $file->setReviewed($reviewed_file);
            $file->setName('Review');
            $tagid = 3;
            $tag = $em->getRepository('MarcaTagBundle:Tag')->find($tagid);
            $file->addTag($tag);
        }
        
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

            return $this->redirect($this->generateUrl('doc_show', array('id' => $doc->getId(), 'courseid'=> $courseid, 'view' => $view)));
            
        }

        return array(
            'doc' => $doc,
            'form'   => $form->createView()
        );
    }
    
  
    /**
     * Displays a form to create a new Doc entity.
     *
     * @Route("/{courseid}/{resource}/{fileid}/review", name="doc_review")
     * @Template("MarcaDocBundle:Doc:new.html.twig")
     */
    public function reviewAction($fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        
        $markupsets = $course->getMarkupsets();
        
        $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $reviewed_body = $reviewed_file->getDoc()->getBody();
        $doc = new Doc();
        $doc->setBody($reviewed_body);

        $form   = $this->createForm(new DocType(), $doc);

        return array(
            'doc' => $doc,
            'fileid' => $fileid,
            'markupsets'      => $markupsets,
            'form'   => $form->createView()
        );
    }    
       

    /**
     * Displays a form to edit an existing Doc entity.
     *
     * @Route("/{courseid}/{id}/{view}/edit", name="doc_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();

        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $file = $doc->getFile();
        $markupsets = $course->getMarkupsets();
        
        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $editForm = $this->createForm(new DocType(), $doc);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'file'        => $file,
            'markupsets'      => $markupsets,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Doc entity.
     *
     * @Route("/{courseid}/{id}/{view}/update", name="doc_update")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:edit.html.twig")
     */
    public function updateAction($id,$courseid,$view)
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

            return $this->redirect($this->generateUrl('doc_show', array('id' => $id, 'courseid'=> $courseid, 'view' => $view)));
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
        $tagid = 5;
        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($tagid);
        
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
            $autosaveFile->addTag($tag);
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
    
 
    /**
     * Creates HTML for printing
     *
     * @Route("/{courseid}/{id}/print", name="doc_print")
     * @Template("MarcaDocBundle:Doc:pdf.html.twig")
     */
    public function htmlPrintAction($id)
    {    
        $ip = $this->get('request')->getClientIp();
        $em = $this->getEm();
        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id); 

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        return array('doc' => $doc, 'ip' => $ip);
    }
    
    
    /**
     * Creates a pdf of a Doc for printing.
     *
     * @Route("/{courseid}/{id}/pdf", name="doc_pdf")
     */    
    public function createPdfAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $em = $this->getEm();
        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $name = $doc->getFile()->getName();
        $filename = 'attachment; filename="'.$name.'.pdf"';
        
        $pageUrl = $this->generateUrl('doc_print', array('id'=> $id,'courseid'=> $courseid),  true); // use absolute path!

        return new Response(
        $this->get('knp_snappy.pdf')->getOutput($pageUrl),
          200,
        array('Content-Type'=> 'application/force-download', 'Content-Disposition'  => $filename )
         );
    }
    

}
