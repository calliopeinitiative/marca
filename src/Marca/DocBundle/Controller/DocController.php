<?php

namespace Marca\DocBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Marca\DocBundle\Entity\Doc;
use Marca\DocBundle\Entity\Autosave;
use Marca\DocBundle\Form\DocType;
use Marca\FileBundle\Entity\File;
use Marca\CourseBundle\Entity\Project;
use Marca\CourseBundle\Entity\Roll;

/**
 * Doc controller.
 *
 * @Route("/doc")
 */
class DocController extends Controller
{

    /**
     * Finds and displays a Doc entity.
     *
     * @Route("/{courseid}/{id}/{view}/show", name="doc_show")
     * @Template()
     */
    public function showAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();
        $user = $this->getUser();
        $role = $this->getCourseRole();

        $file = $em->getRepository('MarcaFileBundle:File')->find($id);

        $doc = $file->getDoc();
        $text = $doc->getBody();
        $count = str_word_count($text);
        $file_owner = $file->getUser();

        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);


        $review_file = $file->getReviewed();
        if ($review_file)
           {
               $parent_file = $em->getRepository('MarcaFileBundle:File')->find($review_file->getId());
               $review_owner = $review_file->getUser();
           }
        else
            {
                $parent_file = $file;
                $review_owner = $file_owner;
            }
        $fileid = $parent_file->getId();
        $file_access = $file->getAccess();
        
        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByCourse($course);
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($fileid);
        $local_resource = $projects = $em->getRepository('MarcaCourseBundle:Project')->findResourcesInSortOrder($course);

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }
        else if ($file_owner != $user && $review_owner != $user && $file_access==0 && $role != 2 )  {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'roll' => $roll,
            'role'      => $role,
            'count'=> $count,
            'file'        => $file,
            'parent_file'        => $parent_file,
            'markup' => $markup,
            'reviews' => $reviews,
            'local_resource' => $local_resource,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Finds and displays a Doc entity.
     *
     * @Route("/{courseid}/{id}/show_ajax", name="doc_show_ajax")
     * @Template("MarcaDocBundle:Doc:show.html.twig")
     */
    public function show_ajaxAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $course = $this->getCourse();
        $user = $this->getUser();
        $role = $this->getCourseRole();

        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $fileid = $file->getId();
        $doc = $file->getDoc();
        $file_owner = $file->getUser();
        $review_file = $file->getReviewed();
        if ($review_file) {$review_owner = $review_file->getUser();} else {$review_owner = $file_owner;}
        $file_access = $file->getAccess();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByCourse($course);
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($fileid);

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }
        else if ($file_owner != $user && $review_owner != $user && $file_access==0 && $role != 2 )  {
            throw new AccessDeniedException();
        }

        return array(
            'doc'      => $doc,
            'role'      => $role,
            'file'        => $file,
            'markup' => $markup,
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
        $course = $this->getCourse();
        $user = $this->getUser();
        $role = $this->getCourseRole();
        $type =2;
        $pages = $em->getRepository('MarcaHomeBundle:Page')->findPageByType($type);

        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        if($user != $file->getUser()){
            throw new AccessDeniedException();
        };
        $doc = $file->getDoc();
        $markupsets = $course->getMarkupsets();
        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($id);
        
        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }

        $editForm = $this->createForm(new DocType(), $doc);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'doc'      => $doc,
            'file'        => $file,
            'pages'        => $pages,
            'role'      => $role,
            'markupsets'      => $markupsets,
            'reviews' => $reviews,
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
        $fileid = $doc->getFile()->getId();

        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }
        
        
        $editForm   = $this->createForm(new DocType(), $doc);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($doc);
            $em->flush();

            return $this->redirect($this->generateUrl('doc_show', array('id' => $fileid, 'courseid'=> $courseid, 'view' => $view)));
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
        
        $request = $this->getRequest();
        $test = $request->request->get('docBody');
        
        if (!$doc) {
            throw $this->createNotFoundException('Unable to find Doc entity.');
        }
        
        
            $doc->setBody($test);
            $em->persist($doc);
        
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

        $form->handleRequest($request);

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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole();
        $doc = $em->getRepository('MarcaDocBundle:Doc')->find($id);
        $file = $doc->getFile();
        $file_owner = $file->getUser();
        $review_file = $file->getReviewed();
        if ($review_file) {$review_owner = $review_file->getUser();} else {$review_owner = $file_owner;}
        $file_access = $file->getAccess();
        
        $name = $file->getName();
        $filename = 'attachment; filename="'.$name.'.pdf"';
        
        if ($file_owner != $user && $review_owner != $user && $file_access==0 && $role != 2 )  {
            throw new AccessDeniedException();
        }
        
        $pageUrl = $this->generateUrl('doc_print', array('id'=> $id,'courseid'=> $courseid),  true); // use absolute path!

        return new Response(
        $this->get('knp_snappy.pdf')->getOutput($pageUrl),
          200,
        array('Content-Type'=> 'application/force-download', 'Content-Disposition'  => $filename )
         );
    }
    

}
