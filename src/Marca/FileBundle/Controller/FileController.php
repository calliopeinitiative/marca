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
     * Lists all File entities.
     *
     * @Route("/{courseid}", name="file")
     * @Template()
     */
    public function indexAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $scope = 'mine';
        $project = 'recent';
        $tag = 0;
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        
        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($project, $user, $scope, $course, $tag);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);
        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($tag);
        return array('files' => $files, 'projects' => $projects, 'active_project' => $project, 'scope'=> $scope, 'tags' => $tags, 'tag' => $tag);
    }

    /**
     * Lists all File entities by Project.
     *
     * @Route("/{courseid}/{project}/{tag}/{scope}/list", name="file_list")
     * @Template("MarcaFileBundle:File:index.html.twig")
     */
    public function indexByProjectAction($project, $scope, $courseid, $tag)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($project, $user, $scope, $course, $tag);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);
        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($tag);
        return array('files' => $files, 'projects' => $projects, 'active_project' => $project, 'scope'=> $scope, 'tags' => $tags, 'tag' => $tag);
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
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);

        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByTag($project, $user, $scope, $course, $tag);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);
        
        return array('files' => $files, 'projects' => $projects, 'active_project' => $project, 'scope'=> $scope, 'tags' => $tags, 'tag' => $tag);
    }    
    
    /**
     * Finds and displays a File entity.
     *
     * @Route("/{courseid}/{id}/show", name="file_show")
     * @Template()
     */
    public function showAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $file = $em->getRepository('MarcaFileBundle:File')->find($id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'file'      => $file,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new File entity.
     *
     * @Route("/{courseid}/new", name="file_new")
     * @Template()
     */
    public function newAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $file = new File();
        $form   = $this->createForm(new FileType(), $file);

        return array(
            'file' => $file,
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
        $request = $this->getRequest();
        $form    = $this->createForm(new FileType(), $file);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($file);
            $em->flush();

            return $this->redirect($this->generateUrl('file_show', array('id' => $file->getId(),'courseid'=> $courseid,)));
            
        }

        return array(
            'file' => $file,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{courseid}/{id}/edit", name="file_edit")
     * @Template()
     */
    public function editAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        
        $em = $this->getEm();
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
        $options = array('courseid' => $courseid);
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);

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

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($file);
            $em->flush();

            return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'sort'=>'updated','scope'=>'mine','project'=>$project,)));
        }

        return array(
            'file'      => $file,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    
        
    /**
     * Deletes a File entity.
     *
     * @Route("/{courseid}/{id}/delete", name="file_delete")
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
            $file = $em->getRepository('MarcaFileBundle:File')->find($id);
            $doc = $entity->getDoc();
            if (!$file) {
                throw $this->createNotFoundException('Unable to find File entity.');
            }

            $em->remove($file);
            if ($doc) {
            $em->remove($doc);
            }
            $em->flush();
        }

        return $this->redirect($this->generateUrl('file', array('courseid'=> $courseid,)));
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
     * @Route("/{courseid}/upload", name="file_upload")
     * @Template()
     */    
     public function uploadAction($courseid)
     {
         $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
         $this->restrictAccessTo($allowed);
        
         $em = $this->getEm();
         $user = $this->getUser();
         $userid = $user->getId();
         $course = $this->getCourse();
         $courseid = $course->getId();
         $options = array('courseid' => $courseid);
         $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetIdByCourse($course);
         $file = new File();
         $file->setUser($user);
         $file->setCourse($course);
         $form = $this->createForm(new UploadType($options), $file);

         if ($this->getRequest()->getMethod() === 'POST') {
             $form->bindRequest($this->getRequest());
             if ($form->isValid()) {
                 $em = $this->getEm();
                 $file->upload($userid, $courseid);
                 $em->persist($file);
                 $em->flush(); 
                 return $this->redirect($this->generateUrl('file', array('courseid'=> $courseid,)));
             }
             
         }

    return array('form' => $form->createView(),'tags'  => $tags,);
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
		$response->setContent( file_get_contents( $file->getAbsolutePath() ));
		
		$response->send();
		
		return $response;
	}     
}
