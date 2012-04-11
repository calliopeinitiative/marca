<?php

namespace Marca\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Marca\FileBundle\Entity\File;
use Marca\FileBundle\Form\FileType;
use Marca\FileBundle\Form\UploadType;
use Marca\TagBundle\Entity\Tagset;
use Marca\UserBundle\Entity\Profile;
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
     * @Route("/", name="file")
     * @Template()
     */
    public function indexAction()
    {   
        $sort = 'u';
        $id = 0;
        $em = $this->getDoctrine()->getEntityManager();
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        $courseid = $this->get('request')->getSession()->get('courseid');
        $entities = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($id, $userid, $sort, $courseid);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($courseid);
        $projectid = 0;
        return array('entities' => $entities, 'projects' => $projects, 'projectid' => $projectid);
    }

    /**
     * Lists all File entities by Project.
     *
     * @Route("/{id}/{sort}/project", name="file_project")
     * @Template("MarcaFileBundle:File:index.html.twig")
     */
    public function indexByProjectAction($id, $sort)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        $courseid = $this->get('request')->getSession()->get('courseid');
        $entities = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($id, $userid, $sort, $courseid);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($courseid);
        $projectid = $id;
        return array('entities' => $entities, 'projects' => $projects, 'projectid' => $projectid);
    }   
       
    
    /**
     * Finds and displays a File entity.
     *
     * @Route("/{id}/show", name="file_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new File entity.
     *
     * @Route("/new", name="file_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new File();
        $form   = $this->createForm(new FileType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new File entity.
     *
     * @Route("/create", name="file_create")
     * @Method("post")
     * @Template("MarcaFileBundle:File:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new File();
        $request = $this->getRequest();
        $form    = $this->createForm(new FileType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('file_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{id}/edit", name="file_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $courseid = $this->get('request')->getSession()->get('courseid');
        $options = array('courseid' => $courseid);

        $entity = $em->getRepository('MarcaFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $editForm = $this->createForm(new FileType($options), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing File entity.
     *
     * @Route("/{id}/update", name="file_update")
     * @Method("post")
     * @Template("MarcaFileBundle:File:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $courseid = $this->get('request')->getSession()->get('courseid');
        $options = array('courseid' => $courseid);
        $entity = $em->getRepository('MarcaFileBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $editForm   = $this->createForm(new FileType($options), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('file'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a File entity.
     *
     * @Route("/{id}/delete", name="file_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaFileBundle:File')->find($id);
            $doc = $entity->getDoc();
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find File entity.');
            }

            $em->remove($entity);
            if ($doc) {
            $em->remove($doc);
            }
            $em->flush();
        }

        return $this->redirect($this->generateUrl('file'));
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
     * @Route("/upload", name="file_upload")
     * @Template()
     */    
     public function uploadAction()
     {
         $em = $this->getDoctrine()->getEntityManager();
         $username = $this->get('security.context')->getToken()->getUsername();
         $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
         $courseid = $this->get('request')->getSession()->get('courseid');
         $options = array('courseid' => $courseid);
         $file = new File();
         $file->setUserid($userid);
         $file->setCourseid($courseid);
         $form = $this->createForm(new UploadType($options), $file);

         if ($this->getRequest()->getMethod() === 'POST') {
             $form->bindRequest($this->getRequest());
             if ($form->isValid()) {
                 $em = $this->getDoctrine()->getEntityManager();
                 $file->upload($userid, $courseid);
                 $em->persist($file);
                 $em->flush(); 
                 return $this->redirect($this->generateUrl('file'));
             }
             
         }

    return array('form' => $form->createView());
     } 
 
    /**
     * Finds and displays a File.
     *
     * @Route("/{id}/view", name="file_view")
     * 
     */     
    public function viewAction($id)
	{
             $em = $this->getDoctrine()->getEntityManager();
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
