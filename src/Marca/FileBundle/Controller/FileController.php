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
use Marca\FileBundle\Form\UploadType;
use Marca\TagBundle\Entity\Tagset;

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
     * @Route("/{courseid}/{project}/{tag}/{scope}/{resource}/list", name="file_list")
     * @Template("MarcaFileBundle:File:index.html.twig")
     */
    public function indexByProjectAction($project, $scope, $courseid, $tag, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($project, $user, $scope, $course, $tag, $resource);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);
        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($tag);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $files = $paginator->paginate($files,$this->get('request')->query->get('page', 1),10);

        return array('files' => $files, 'projects' => $projects, 'active_project' => $project, 'tags' => $tags, 'course' => $course);
    }  
       
      /**
     * Lists all File entities by Project.
     *
     * @Route("/{courseid}/{project}/{tag}/{scope}/list_by_tag", name="file_tag_list")
     * @Template("MarcaFileBundle:File:index.html.twig")
     */
    public function indexByTagAction($project, $scope, $courseid, $tag)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $resource ='f';
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByTag($project, $user, $scope, $course, $tag);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $files = $paginator->paginate($files,$this->get('request')->query->get('page', 1),10);

        return array('files' => $files, 'projects' => $projects, 'active_project' => $project, 'tags' => $tags, 'course' => $course);
    }     
    
    /**
     * Finds and displays a File entity.
     *
     * @Route("/{courseid}/{id}/{project}/{tag}/{scope}/{resource}/show", name="file_show")
     * @Template()
     */
    public function showAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $resource ='f';
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }


        return array('file' => $file,'projects' => $projects, 'active_project' => '0', 'tags' => $tags, 'course' => $course);
    }

    /**
     * Displays a form to create a new File entity.
     *
     * @Route("/{courseid}/{resource}/{tag}/new", name="file_new")
     * @Template()
     */
    public function newAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $file = new File();
        $em = $this->getEm();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $options = array('courseid' => $courseid);
        $form   = $this->createForm(new FileType($options), $file);

        return array(
            'file'      => $file,
            'tags'        => $tags,
            'course' => $course,
            'form'   => $form->createView()
        );
    }
       

    /**
     * Creates a new File entity.
     *
     * @Route("/{courseid}/create", name="file_create")
     * @Method("post")
     * @Template("MarcaFileBundle:File:new.html.twig")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $file  = new File();
        $em = $this->getEm();
        $user = $this->getUser();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $file->setUser($user);
        $file->setCourse($course);
        $options = array('courseid' => $courseid);
        $request = $this->getRequest();
        $postData = $request->get('marca_filebundle_filetype');
        $project = $postData['project'];
        $resource = $em->getRepository('MarcaCourseBundle:Project')->find($project);
        $resource = $resource->getResource();
        if (!$resource)
        {$resource = '0';}
        $form    = $this->createForm(new FileType($options), $file);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($file);
            $em->flush();

        return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'sort'=>'updated','scope'=>'mine','project'=>$project, 'tag'=>'0', 'resource'=>$resource)));
            
        }

        return array(
            'file' => $file,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{courseid}/{resource}/{tag}/{id}/edit", name="file_edit")
     * @Template()
     */
    public function editAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $options = array('courseid' => $courseid);
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
              
        if (!$file) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }
        elseif($user != $file->getUser()){
            throw new AccessDeniedException();
        }        

        $editForm = $this->createForm(new FileType($options), $file);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'file'      => $file,
            'tags'        => $tags,
            'course' => $course,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing File entity.
     *
     * @Route("/{courseid}/{id}/update", name="file_update")
     * @Method("post")
     * @Template("MarcaFileBundle:File:edit.html.twig")
     */
    public function updateAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $options = array('courseid' => $courseid);
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }
        elseif($user != $file->getUser()){
            throw new AccessDeniedException();
        } 

        $editForm   = $this->createForm(new FileType($options), $file);
        $deleteForm = $this->createDeleteForm($id);

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

            return $this->redirect($this->generateUrl('file_show', array('id'=> $id,'courseid'=> $courseid,'sort'=>'updated','scope'=>'mine','project'=>$project, 'tag'=>'0', 'resource'=>$resource)));
        }

        return array(
            'file'      => $file,
            'tags'        => $tags,
            'course' => $course,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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

       return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'sort'=>'updated','scope'=>'mine','project'=>'recent', 'tag'=>'0', 'resource'=>$resource)));
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
     * @Template()
     */    
     public function uploadAction($courseid, $resource)
     {
         $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
         $this->restrictAccessTo($allowed);
        
         $em = $this->getEm();
         $user = $this->getUser();
         $userid = $user->getId();
         $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

         $options = array('courseid' => $courseid);
         
         $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($courseid);
         $file = new File();
         $file->setUser($user);
         $file->setCourse($course);
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
                 return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'sort'=>'updated','scope'=>'mine','project'=>$project, 'tag'=>'0', 'resource'=>$resource)));
             }
             
         }

    return array('form' => $form->createView(),'tags'  => $tags,'course' => $course,);
     } 
 
    /**
     * Finds and displays a File.
     *
     * @Route("/{courseid}/{id}/view", name="file_view")
     * 
     */     
    public function viewAction($id)
	{
             $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
             $this->restrictAccessTo($allowed);
        
             $em = $this->getEm();
             $file = $em->getRepository('MarcaFileBundle:File')->find($id);
             $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
             $path = $helper->asset($file, 'file');
             $ext = $file->getExt();
		
		$response = new Response();
		
		$response->setStatusCode(200);
                switch ($ext) {
                      case "png":
                      $response->headers->set('Content-Type', 'image/png');
                      break;
                      case "gif":
                      $response->headers->set('Content-Type', 'image/gif');
                      break;
                      case "jpeg":
                      $response->headers->set('Content-Type', 'image/jpeg');
                      break;
                      case "jpg":
                      $response->headers->set('Content-Type', 'image/jpeg');
                      break;
                      case "odt":
                      $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.text');
                      break;
                      case "ods":
                      $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.spreadsheet');
                      break;
                      case "odp":
                      $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.presentation');
                      break;
                      case "doc":
                      $response->headers->set('Content-Type', 'application/msword');
                      break;
                      case "ppt":
                      $response->headers->set('Content-Type', 'application/mspowerpoint');
                      break;
                      case "xls":
                      $response->headers->set('Content-Type', 'application/x-msexcel');
                      break;                  
                      case "pdf":
                      $response->headers->set('Content-Type', 'application/pdf');
                      break;
                      default:
                      $response->headers->set('Content-Type', 'application/octet-stream');    
                      }
		$response->setContent( file_get_contents( $path));
		
		$response->send();
		
		return $response;
	}     
}
