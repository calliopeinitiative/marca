<?php

namespace Marca\FileBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Marca\FileBundle\Entity\File;
use Marca\FileBundle\Form\FileType;
use Marca\FileBundle\Form\LinkType;
use Marca\FileBundle\Form\DocType;
use Marca\FileBundle\Form\ReviewType;
use Marca\FileBundle\Form\UploadType;
use Marca\TagBundle\Entity\Tagset;
use Marca\DocBundle\Entity\Doc;

/**
 * File controller.
 *
 * @Route("/file")
 */
class FileController extends Controller
{ 

    /**
     * Lists all File entities by Project.
     *
     * @Route("/{courseid}/{project}/{tag}/{scope}/{user}/{resource}/{userid}/list", name="file_list")
     * @Template("MarcaFileBundle:File:index.html.twig")
     */
    public function indexByProjectAction($project, $scope, $courseid, $tag, $resource,$userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        if ($resource == 0) {
        $session = $this->get('session'); 
        $request = $this->getRequest();
        $session->set('referrer', $request->getRequestUri());
        }
        
        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole();
        $byuser = $course = $em->getRepository('MarcaUserBundle:User')->find($userid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        if ($tag == 3) {
        $files = $em->getRepository('MarcaFileBundle:File')->findPeerReviewFiles($project, $user, $scope, $course, $tag, $resource, $byuser, $role);    
        }
        else
        {
        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($project, $user, $scope, $course, $tag, $resource, $byuser, $role);
        }
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);
        $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();
        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($tag);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $files = $paginator->paginate($files,$this->get('request')->query->get('page', 1),100);
        $count = $files->getTotalItemCount();

        return array('files' => $files, 'count' => $count, 'projects' => $projects, 'active_project' => $project, 
            'tags' => $tags, 'systemtags' => $systemtags, 'tag' => $tag, 'byuser' => $byuser, 'course' => $course, 'roll' => $roll, 'role'=> $role);
    }  
           
 
    /**
     * Finds and displays a File entity.
     *
     * @Route("/{courseid}/{id}/{resource}/show_modal", name="file_show_modal")
     * @Template("MarcaFileBundle:File:show_modal.html.twig")
     */
    public function showModalAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);    
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $deleteForm = $this->createDeleteForm($id);
        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }
        return array('file' => $file, 'delete_form' => $deleteForm->createView(),);
    }
    
    /**
     * Finds and displays a File entity.
     *
     * @Route("/{courseid}/{id}/toggle_release", name="file_toggle_release")
     */
    public function toggleReleaseAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed); 
        $session = $this->getRequest()->getSession();
        if (!$session){$uri='../../../file/1/recent/0/mine/0/0/0/list';}
        else {
        $uri = $session->get('referrer');
        }
        
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        if ($file->getAccess()==2){$file->setAccess('0');}
        else {$file->setAccess('2');}
        $em->persist($file);
        $em->flush();

        return $this->redirect($uri);
        
    }
    
    /**
     * Finds and displays a File entity.
     *
     * @Route("/{courseid}/release_all", name="file_release_all")
     */
    public function releaseAllAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed); 
        $session = $this->getRequest()->getSession();
        if (!$session){$uri='../../../file/1/recent/0/mine/0/0/0/list';}
        else {
        $uri = $session->get('referrer');
        }
        $user = $this->getUser();
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $files = $em->getRepository('MarcaFileBundle:File')->findHidden($user,$course);
        foreach($files as $file){
           $file->setAccess('0');
           $em->persist($file);
         }
         $em->flush();

        return $this->redirect($uri);
    }    
    
       
    /**
     * Displays a form to create a new File entity for a LINK listing.
     *
     * @Route("/{courseid}/{resource}/{tag}/{type}/{fileid}/new_modal", name="file_new_modal")
     * @Template("MarcaFileBundle:File:new_modal.html.twig")
     */
    public function newModalAction($courseid, $resource, $tag, $type, $fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $user = $this->getUser();     
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project = $em->getRepository('MarcaCourseBundle:Project')->findProjectByCourse($course, $resource);
        $options = array('courseid' => $courseid, 'resource'=> $resource);
        
        $file = new File();
        $file->setProject($project);
        $file->setUser($user);
        $file->setCourse($course);
        if ($type == 'link') {
        $file->setName('New Link');
        $file->setUrl('http://newlink.edu');
        $form   = $this->createForm(new LinkType($options), $file);
        return array(
            'file'      => $file,
            'resource'      => $resource,
            'tag'      => $tag,
            'type'      => $type,
            'tags'        => $tags,
            'roll'        => $roll,
            'course' => $course,
            'form'   => $form->createView()
        );
        }
        elseif ($type == 'doc') {
        $file->setName('New Document'); 
        $form   = $this->createForm(new DocType($options), $file);
        return array(
            'file'      => $file,
            'resource'      => $resource,
            'tag'      => $tag,
            'type'      => $type,
            'tags'        => $tags,
            'roll'        => $roll,
            'course' => $course,
            'form'   => $form->createView()
        );
        }
        elseif ($type == 'review'){
           $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
           $file->setName('Review');
           $doc = new Doc();
           $doc->setFile($file);
           $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
           $file->setReviewed($reviewed_file);
           $file->addTag($em->getRepository('MarcaTagBundle:Tag')->find(3));
           $doc->setBody($reviewed_file->getDoc()->getBody());
           $em->persist($doc);
           $em->persist($file);
           $em->flush();
           return $this->redirect($this->generateUrl('doc_edit', array('courseid'=> $courseid,'id'=> $doc->getId(),'view'=>'app')));
        }
        elseif ($type == 'instr_review'){
           $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
           $file->setName('Review');
           $file->setAccess('2');
           $doc = new Doc();
           $doc->setFile($file);
           $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
           $file->setReviewed($reviewed_file);
           $file->addTag($em->getRepository('MarcaTagBundle:Tag')->find(3));
           $file->setAccess('2');
           $doc->setBody($reviewed_file->getDoc()->getBody());
           $em->persist($doc);
           $em->persist($file);
           $em->flush();
           return $this->redirect($this->generateUrl('doc_edit', array('courseid'=> $courseid,'id'=> $doc->getId(),'view'=>'app')));
        }        
        elseif ($type == 'saveas'){
           $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
           $file->setName('SaveAs Document');
           $form   = $this->createForm(new DocType($options), $file);
           return array(
                'file'      => $file,
                'resource'      => $resource,
                'tag'      => $tag,
                'type'      => $type,
                'tags'        => $tags,
                'roll'        => $roll,
                'course' => $course,
                'form'   => $form->createView()
            );  
        }        
    }
    

    
    /**
     * Creates a new File entity.
     *
     * @Route("/{courseid}/{resource}/{tag}/{type}/{fileid}/create", defaults={ "fileid" = 0 }, name="file_create")
     * @Method("post")
     * @Template("MarcaFileBundle:File:new.html.twig")
     */
    public function createAction($courseid,$resource, $tag, $type, $fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $file  = new File();
        $em = $this->getEm();
        $user = $this->getUser();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $file->setUser($user);
        $file->setCourse($course);
        $options = array('courseid' => $courseid, 'resource'=> $resource);
        $request = $this->getRequest();
        $postData = $request->get('marca_filebundle_filetype');
        $project = $postData['project'];
        $resource = $em->getRepository('MarcaCourseBundle:Project')->find($project);
        $resource = $resource->getResource();
        if (!$resource)
        {$resource = '0';}
        $form    = $this->createForm(new FileType($options), $file);
        $form->bindRequest($request);
        if ($type == 'doc') {
        $doc  = new Doc();    
        $doc->setFile($file); 
        $doc->setBody('<p></p>'); 
        }
        elseif ($type == 'review'){

        }        
        elseif ($type == 'saveas'){
            $doc = new Doc();
            $doc->setFile($file);
            $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
            $doc->setBody($reviewed_file->getDoc()->getBody());
        }        
        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($file);
            if ($type == 'doc' || $type == 'review'|| $type == 'instr_review' || $type == 'saveas') {
            $em->persist($doc);
            }
            $em->flush();
            
        if ($type == 'link') {
        return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'id'=> $file->getId(),'scope'=>'mine','project'=>$project, 'tag'=>'0', 'userid'=>'0', 'resource'=>$resource, 'user'=>'0')));
        }
        elseif ($type == 'doc' || $type == 'review' || $type == 'instr_review' || $type == 'saveas') {
        return $this->redirect($this->generateUrl('doc_edit', array('courseid'=> $courseid,'id'=> $doc->getId(),'view'=>'app')));
        }
        
        }

        return array(
            'file'      => $file,
            'resource'      => $resource,
            'tag'      => $tag,
            'type'      => $type,
            'tags'        => $tags,
            'roll'        => $roll,
            'course' => $course,
            'form'   => $form->createView()
        );
    }
    

    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{courseid}/{resource}/{tag}/{id}/edit_modal", name="file_edit_modal")
     * @Template("MarcaFileBundle:File:edit_modal.html.twig")
     */
    public function editModalAction($id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $options = array('courseid' => $courseid, 'resource'=> $resource);
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $url = $file->getUrl();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
              
        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }
        elseif($user != $file->getUser()){
            throw new AccessDeniedException();
        };   
        
        //test to see if this is a link update
        if (empty($url)) {
            $editForm = $this->createForm(new FileType($options), $file);
        }
        else {
            $editForm = $this->createForm(new LinkType($options), $file);
        }


        return array(
            'file'      => $file,
            'tags'        => $tags,
            'roll'        => $roll,
            'course' => $course,
            'edit_form'   => $editForm->createView(),
        );
    }
    
    
    /**
     * Edits an existing File entity.
     *
     * @Route("/{courseid}/{resource}/{id}/update", name="file_update")
     * @Method("post")
     * @Template("MarcaFileBundle:File:edit.html.twig")
     */
    public function updateAction($id,$courseid,$resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $options = array('courseid' => $courseid, 'resource'=> $resource);
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }
        elseif($user != $file->getUser()){
            throw new AccessDeniedException();
        } 

        $editForm   = $this->createForm(new FileType($options), $file);

        $request = $this->getRequest();
        $postData = $request->get('marca_filebundle_filetype');
        $project = $postData['project'];
        $resource = $em->getRepository('MarcaCourseBundle:Project')->find($project);
        $resource = $resource->getResource();
        if (!$resource)
        {$resource = '0';}
        
        
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($file);
            $em->flush();

            return $this->redirect($this->generateUrl('file_list', array('id'=> $id,'courseid'=> $courseid,'scope'=>'mine','project'=>'recent', 'tag'=>'0', 'userid'=>'0','resource'=>$resource, 'user'=>'0')));
        }

        return array(
            'file'      => $file,
            'tags'        => $tags,
            'roll'        => $roll,
            'course' => $course,
            'edit_form'   => $editForm->createView(),
        );
    }

    
        
    /**
     * Deletes a File entity.
     *
     * @Route("/{courseid}/{resource}/{id}/delete", name="file_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $file = $em->getRepository('MarcaFileBundle:File')->find($id);
            $doc = $file->getDoc();
            if (!$file) {
                throw $this->createNotFoundException('Unable to find File entity.');
            }

            
            if ($doc) {
            $em->remove($doc);
            $em->getRepository('MarcaFileBundle:File')->deleteEdoc($id);
            }
            else {$em->remove($file);}
            $em->flush();
        }

       return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'sort'=>'updated','scope'=>'mine','project'=>'recent', 'tag'=>'0', 'userid'=>'0','resource'=>$resource, 'user'=>'0')));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Uploads a file with a Document entity.
     *
     * @Route("/{courseid}/{resource}/{tag}/upload", name="file_upload")
     * @Template("MarcaFileBundle:File:upload.html.twig")
     */    
     public function uploadAction($courseid, $resource)
     {
         $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
         $this->restrictAccessTo($allowed);
        
         $em = $this->getEm();
         $user = $this->getUser();
         $userid = $user->getId();
         $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
         $project = $em->getRepository('MarcaCourseBundle:Project')->findProjectByCourse($course, $resource);
         $options = array('courseid' => $courseid, 'resource'=> $resource);
         $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();
         
         $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
         $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
         $file = new File();
         $file->setUser($user);
         $file->setCourse($course);
         $file->setName('New Upload');
         $file->setProject($project);
         $form = $this->createForm(new UploadType($options), $file);
         
        $request = $this->getRequest();
        $postData = $request->get('marca_filebundle_filetype');
        $project = $postData['project'];
        if (!$resource)
        {$resource = '0';}
        

         if ($this->getRequest()->getMethod() === 'POST') {
             $form->bindRequest($this->getRequest());
             if ($form->isValid()) {
                 $em = $this->getEm();
                 $em->persist($file);
                 $em->flush(); 
                 return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'id'=> $file->getId(),'sort'=>'updated','scope'=>'mine','project'=>'recent', 'tag'=>'0', 'userid'=>'0','resource'=>$resource, 'user'=>'0')));
             }
             
         }

    return array('form' => $form->createView(),'tags'  => $tags, 'systemtags'  => $systemtags, 'roll'  => $roll,'course' => $course,);
     } 
 
    /**
     * Finds and displays a File.
     *
     * @Route("/{courseid}/{id}/view", name="file_view")
     * 
     */     
    public function viewAction($id)
	{
             $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
             $this->restrictAccessTo($allowed);
        
             $em = $this->getEm();
             $file = $em->getRepository('MarcaFileBundle:File')->find($id);
             $name = $file->getName();
             $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
             $path = $helper->asset($file, 'file');
             $ext = $file->getExt();
             $filename = $name.'.'.$ext;
		
		$response = new Response();
		
		$response->setStatusCode(200);
                switch ($ext) {
                      case "png":
                      $response->headers->set('Content-Type', 'image/png');
                      $response->headers->set('Content-Disposition', 'filename="'.$filename.'"');
                      break;
                      case "gif":
                      $response->headers->set('Content-Type', 'image/gif');
                      $response->headers->set('Content-Disposition', 'filename="'.$filename.'"');    
                      break;
                      case "jpeg":
                      $response->headers->set('Content-Type', 'image/jpeg');
                      $response->headers->set('Content-Disposition', 'filename="'.$filename.'"');     
                      break;
                      case "jpg":
                      $response->headers->set('Content-Type', 'image/jpeg');
                      $response->headers->set('Content-Disposition', 'filename="'.$filename.'"');     
                      break;
                      case "odt":
                      $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.text');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;
                      case "ods":
                      $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.spreadsheet');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;
                      case "odp":
                      $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.presentation');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;
                      case "doc":
                      $response->headers->set('Content-Type', 'application/vnd.msword');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;
                      case "docx":
                      $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;                  
                      case "ppt":
                      $response->headers->set('Content-Type', 'application/vnd.mspowerpoint');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;
                      case "pptx":
                      $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;                  
                      case "xls":
                      $response->headers->set('Content-Type', 'application/vnd.ms-excel');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;  
                      case "xlsx":
                      $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');     
                      break;                   
                      case "pdf":
                      $response->headers->set('Content-Type', 'application/pdf');
                      $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');   
                      break;
                      default:
                      $response->headers->set('Content-Type', 'application/octet-stream');    
                      }
		$response->setContent( file_get_contents( $path));
		
		$response->send();
		
		return $response;
	} 
        
   /**
     * Finds and displays an XSL transformation of a File entity.
     *
     * @Route("/{courseid}/{id}/{view}/view_odt", name="file_view_odt")
     * @Template("MarcaDocBundle:Doc:show.html.twig")
     */
    public function viewOdtAction($id)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $doc = new Doc();
        $doc->setFile($file);
        $course = $this->getCourse();
        $role = $this->getCourseRole();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByCourse($course);
        
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $odtfile = $helper->asset($file, 'file');
        $xsltfile = __DIR__.'/../../../../src/Marca/DocBundle/Resources/xsl/odt2html.xsl';

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

            $html4doc = $this->odt2html($odtfile, $xsltfile)->render_inline_images()->get_html();
            $doc->setBody($html4doc);
            return array(
            'doc'      => $doc,
            'role'      => $role,    
            'file'        => $file,
            'markup' => $markup,
             );
          

    } 
    
    
 
   /**
     * Finds and displays an XSL transformation of a File entity.
     *
     * @Route("/{courseid}/{id}/{view}/convert_odt", name="file_convert_odt")
     * @Template("MarcaDocBundle:Doc:show.html.twig")
     */
    public function convertOdtAction($id)
    {
        
        $em = $this->getEm();
        $org_file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $org_file_project = $org_file->getProject();
        $org_file_access = $org_file->getAccess();
        $org_file_name = $org_file->getName(). ' converted';
        $user = $this->getUser();
        $course = $this->getCourse();
        $file  = new File();
        $file->setUser($user);
        $file->setCourse($course);
        $file->setProject($org_file_project);
        $file->setAccess($org_file_access);
        $file->setName($org_file_name);
        $doc = new Doc();
        $doc->setFile($file);
        $role = $this->getCourseRole();

        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByCourse($course);
        
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $odtfile = $helper->asset($org_file, 'file');
        $xsltfile = __DIR__.'/../../../../src/Marca/DocBundle/Resources/xsl/odt2html.xsl';

        if (!$org_file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

            $html4doc = $this->odt2html($odtfile, $xsltfile)->render_inline_images()->get_html();
            $doc->setBody($html4doc);
            $em->persist($file);
            $em->persist($doc);
            $em->flush();
            return array(
            'doc'      => $doc,
            'role'      => $role,    
            'file'        => $file,
            'markup' => $markup,
             );
          

    }    
            
                
                
	//------------------------------------------------------------------------------------------
	// ODT 2 HTML
	//------------------------------------------------------------------------------------------
	private $html;
	private $odtZipfilename;

	public function odt2html($odtfile, $xsltfile)
	{
		$xslFile = "odt";
		$xslDoc = new \DOMDocument();
		$xslDoc->load($xsltfile);

		$odtZipfilename = "zip://"  . $odtfile;
		$xmlDoc = new \DOMDocument();
		$xmlDoc->load("$odtZipfilename#content.xml");
		$this->odtZipfilename = $odtZipfilename;

		$proc = new \XSLTProcessor();
		$proc->importStylesheet($xslDoc);
		$html = $proc->transformToXML($xmlDoc);

		$this->html = $html;
                return $this;
	}



	//------------------------------------------------------------------------------------------
	// Chainable methods
	//------------------------------------------------------------------------------------------

	public function render_inline_images()
	{
		$html = $this->html;
		preg_match_all('/Pictures\/.*?.png/', $html, $matches);
		$img_files = $matches[0];
		$img_files_src = array();
		foreach($img_files as $img_file) {
			$img_files_src[$img_file] = $this->odt_picture_to_inline_img($this->odtZipfilename, $img_file);
		}
		foreach($img_files as $img_file) {
			$html = str_replace($img_file, $img_files_src[$img_file], $html);
		}
		$this->html = $html;
		return $this;
	}

	public function save_to_htmlfile($filename, $utf8=TRUE)
	{
		file_put_contents($filename, $this->get_html($utf8));
		return $this;
	}

	//------------------------------------------------------------------------------------------
	// Methods
	//------------------------------------------------------------------------------------------

	public function get_html($utf8=TRUE, $utf8header=TRUE)
	{
		$html = ($utf8header) 
		? '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . PHP_EOL . $this->html 
		: $this->html;

		return ($utf8) ? $html : utf8_decode($html);
	}

	//--------------------------------------------------------------------------------------------------

	private function odt_picture_to_inline_img($odt_zip_filename, $odt_img_filename) {
		$zip_picture_name = $odt_zip_filename . "#$odt_img_filename";
		$file_data = self::file_to_filedata($zip_picture_name);

		$gd_data = self::file_to_gddata($zip_picture_name);
		$gd_data_scaled = self::gddata_scale($gd_data);
		$file_data = self::gddata_to_filedata($gd_data_scaled);

		return self::filedata_to_imagesrc($file_data);
	}

	//------------------------------------------------------------------------------------------
	// Image functions
	//------------------------------------------------------------------------------------------

	static private function filedata_to_uridata($filedata, $mime='image/png') {
		$base64   = base64_encode($filedata);
		return ('data:' . $mime . ';base64,' . $base64);
	}

	static private function filedata_to_imagetag($filedata) {
		$ret = '<img src="';
		$ret .= self::filedata_to_uridata($filedata);
		$ret .=  '" />';
		return $ret;
	}

	static private function filedata_to_imagesrc($filedata) {
		$ret = self::filedata_to_uridata($filedata);
		return $ret;
	}

	static private function file_to_filedata($pngfile_name) {
		return file_get_contents($pngfile_name);
	}

	static private function file_to_gddata($pngfile_name) {
		return imagecreatefrompng($pngfile_name);
	}

	static private function gddata_to_filedata($gddata) {
		ob_start();
		imagepng($gddata);
		imagedestroy($gddata);
		$filedata = ob_get_contents();
		ob_end_clean();
		return $filedata;
	}

	static private function gddata_to_uridata($gddata) {
		$filedata = self::gddata_to_filedata($gddata);
		$uridata = self::filedata_to_uridata($filedata);
		return $uridata;
	}

	static private function gddata_to_imagetag($gddata) {
		$filedata = self::gddata_to_filedata($gddata);
		return self::filedata_to_imagetag($filedata);
	}

	static private function gddata_process($gddata) {
		$color = imagecolorallocate($gddata, 255, 0, 0);
		imagefilledrectangle($gddata, 10, 10, 20, 20, $color);
		$fontname = 'Arial.ttf';
		$font =  dirname(__FILE__).'/fonts/'.$fontname;
		imagettftext($gddata, 20, 0, 50, 50, $color, $font, 'Hello');
		return $gddata;
	}

	static private function gddata_resize($gddata, $new_w, $new_h) {
		$current_w = imageSX($gddata);
		$current_h = imageSY($gddata);
		$new_gddata = ImageCreateTrueColor($new_w, $new_h);
		imagecopyresampled($new_gddata,$gddata,0,0,0,0,$new_w,$new_h,$current_w,$current_h );
		return $new_gddata;
	}

	static private function gddata_scale($gddata, $factor=1) {
		$current_w = imageSX($gddata);
		$current_h = imageSY($gddata);
		$new_w = $current_w * $factor;
		$new_h = $current_h * $factor;
		$new_gddata = ImageCreateTrueColor($new_w, $new_h);
		imagecopyresampled($new_gddata, $gddata,0,0,0,0, $new_w, $new_h, $current_w, $current_h );
		return $new_gddata;
	}                
                
}
