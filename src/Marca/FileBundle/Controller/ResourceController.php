<?php

namespace Marca\FileBundle\Controller;

use Marca\FileBundle\Entity\File;
use Marca\FileBundle\Form\FileType;
use Marca\FileBundle\Form\LinkType;
use Marca\FileBundle\Form\UploadType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Marca\HomeBundle\Controller\Controller;
use Marca\TagBundle\Entity\Tagset;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Resource controller.
 *
 * @Route("/file")
 */
class ResourceController extends Controller
{
    /**
     * Lists all Course entities.
     *
     * @Route("/{courseid}/{resource}/resources_sidebar", name="file_resources_sidebar", defaults={ "tag" = 0,"project" = 0})
     */
    public function createResourcesSidebarAction(Request $request, $courseid, $resource)
    {
        $em = $this->getEm();
        $role = $this->getCourseRole($request);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);
        $default_project = $projects[0]->getId();
        $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();
        $parents = $em->getRepository('MarcaCourseBundle:Course')->findParents($course);
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findResourcesTagsetByCourse($course, $parents);

        return $this->render('MarcaFileBundle::resources_sidebar.html.twig', array(
            'projects' => $projects,
            'default_resource' => $default_project,
            'tags' => $tags,
            'systemtags' => $systemtags,
            'role' => $role
        ));
    }

    /**
     * Lists all File entities by Project; used for both Projects and Resources.
     *
     * @Route("/{courseid}/{project}/{resource}/filebyproject", name="file_listbyproject")
     */
    public function filesByProjectAction(Request $request, $project, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $role = $this->getCourseRole($request);
        $access = $role;

        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($project, $access);
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($project);

        $session = $this->get('session');

        if ($resource == 0) {
            $template = 'MarcaFileBundle:File:files_index.html.twig';
            $session->set('referrer', $request->getRequestUri());
        } else {
            $template = 'MarcaFileBundle:File:resources_index.html.twig';
            $session->set('resource_referrer', $request->getRequestUri());
        }

        $heading = $project->getCourse()->getName().': '. $project->getName();

        return $this->render($template, array(
            'files' => $files,
            'role' => $role,
            'course' => $course,
            'heading' => $heading
        ));
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{courseid}/{resource}/{id}/edit_modal", name="file_edit_modal", defaults={"resource" = 0, "fileid" = 0})
     */
    public function editModalAction(Request $request, $id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        $user = $this->getUser();

        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $url = $file->getUrl();
        $course = $file->getCourse();
        $file_courseid = $course->getId();
        $options = array('courseid' => $file_courseid, 'resource' => $resource);


        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        } elseif ($user != $file->getUser()) {
            throw new AccessDeniedException();
        };

        //test to see if this is a link update
        if (empty($url)) {
            $editForm = $this->createEditForm($file, $courseid, $options, $resource);
        } else {
            $editForm = $this->createEditLinkForm($file, $courseid, $options, $resource);
        }


        return $this->render('MarcaFileBundle:File:edit_modal.html.twig', array(
            'file' => $file,
            'course' => $course,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a File entity.
     *
     * @param File $file
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(File $file, $courseid, $options, $resource)
    {
        $form = $this->createForm(new FileType($options), $file, array(
            'action' => $this->generateUrl('file_update', array('id' => $file->getId(), 'courseid' => $courseid, 'resource' => $resource)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post', 'attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }

    /**
     * Creates a form to edit a File entity.
     *
     * @param File $file
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditLinkForm(File $file, $courseid, $options, $resource)
    {
        $form = $this->createForm(LinkType::class, $file, array(
            'action' => $this->generateUrl('file_update', array('id' => $file->getId(), 'courseid' => $courseid, 'resource' => $resource)),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Post', 'attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }


    /**
     * Edits an existing File entity.
     *
     * @Route("/{courseid}/{resource}/{id}/update", name="file_update", defaults={"resource" = 0, "fileid" = 0})
     * @Method("post")
     * @Template("MarcaFileBundle:File:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        $user = $this->getUser();

        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $course = $file->getCourse();
        $file_courseid = $course->getId();
        $options = array('courseid' => $file_courseid, 'resource' => $resource);


        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        } elseif ($user != $file->getUser()) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(FileType::class, $file, ['options' => $options]);

//        $postData = $request->get('marca_filebundle_filetype');
//        $project = $postData['project'];
//        $resource = $em->getRepository('MarcaCourseBundle:Project')->find($project);
//        $resource = $resource->getResource();
//        if (!$resource) {
//            $resource = '0';
//        }


        $editForm->handleRequest($request);

        $em->persist($file);
        $em->flush();

        $session = $request->getSession();
        if (!$session) {
            $uri = $this->redirect($this->generateUrl('file_list', array('courseid' => $courseid, 'id' => $file->getId(), 'userid' => '0', 'resource' => $resource, 'user' => '0')));
        } else {
            if ($resource == 0) {
                $uri = $session->get('referrer');
            } else {
                $uri = $session->get('resource_referrer');
            }
            return $this->redirect($uri);

        }

    }

    /**
     * Finds and displays a File entity for delete confirmation; used for both Projects and Resources.
     *
     * @Route("/{courseid}/{id}/{resource}/show_modal", name="file_show_modal")
     */
    public function showModalAction(Request $request, $id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $deleteForm = $this->createDeleteForm($id);
        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }
        return $this->render('MarcaFileBundle:File:show_modal.html.twig', array('file' => $file, 'delete_form' => $deleteForm->createView(),));
    }


    /**
     * Deletes a File entity; used for both Projects and Resources.
     *
     * @Route("/{courseid}/{resource}/{id}/delete", name="file_delete", defaults={"resource" = 0})
     * @Method("post")
     */
    public function deleteAction(Request $request, $id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $form = $this->createDeleteForm($id);

        $form->submit($request);

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
            } else {
                $em->remove($file);
            }
            $em->flush();
        }

        $session = $request->getSession();
        if (!$session) {
            $uri = $this->redirect($this->generateUrl('file_list', array('courseid' => $courseid, 'id' => $file->getId(), 'userid' => '0', 'resource' => $resource, 'user' => '0')));
        } else {
            if ($resource == 0) {
                $uri = $session->get('referrer');
            } else {
                $uri = $session->get('resource_referrer');
            }
            return $this->redirect($uri);
        }
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    /**
     * Uploads a file; used for both Projects and Resources.
     *
     * @Route("/{courseid}/{resource}/upload", name="file_upload", defaults={"resource" = 0})
     */
    public function uploadAction(Request $request, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole($request);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project = $course->getProjectDefault();
        $options = array('courseid' => $courseid, 'resource' => $resource, 'review' => 'no');
        $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();

        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $file = new File();
        if ($resource != 0) {
            $file->setAccess(1);
            $project = $em->getRepository('MarcaCourseBundle:Project')->findProjectByCourse($course, $resource);
        }
        $file->setUser($user);
        $file->setCourse($course);
        $file->setProject($project);
        $form = $this->createForm(UploadType::class, $file, ['options' => $options]);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getEm();
                $em->persist($file);
                $em->flush();

                $session = $request->getSession();
                if (!$session) {
                    $uri = $this->redirect($this->generateUrl('file_list', array('courseid' => $courseid, 'id' => $file->getId(), 'userid' => '0', 'resource' => $resource, 'user' => '0')));
                } else {
                    if ($resource == 0) {
                        $uri = $session->get('referrer');
                    } else {
                        $uri = $session->get('resource_referrer');
                    }


                    return $this->redirect($uri);
                }
            }

        }

        return $this->render('MarcaFileBundle:File:upload_modal.html.twig', array(
            'form' => $form->createView(),
            'tags' => $tags,
            'systemtags' => $systemtags,
            'roll' => $roll,
            'role' => $role,
            'course' => $course,));
    }


    /**
     * Finds and downloads a File; used for both Projects and Resources.
     *
     * @Route("/{courseid}/{id}/get/{filename}", name="file_get", defaults={"filename" = "name.ext"})
     *
     */
    public function getAction(Request $request, $id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
        $this->restrictAccessTo($request, $allowed);

        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $name = $file->getName();
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($file, 'file');
        $ext = strtolower($file->getExt());
        $filename = $name . '.' . $ext;

        $response = new Response();

        $response->setStatusCode(200);
        switch ($ext) {
            case "png":
                $response->headers->set('Content-Type', 'image/png');
                $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
                break;
            case "gif":
                $response->headers->set('Content-Type', 'image/gif');
                $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
                break;
            case "jpeg":
                $response->headers->set('Content-Type', 'image/jpeg');
                $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
                break;
            case "jpg":
                $response->headers->set('Content-Type', 'image/jpeg');
                $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
                break;
            case "mpeg":
                $response->headers->set('Content-Type', 'audio/mpeg');
                $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
                break;
            case "mp3":
                $response->headers->set('Content-Type', 'audio/mp3');
                $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
                break;
            case "odt":
                $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.text');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "ods":
                $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.spreadsheet');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "odp":
                $response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.presentation');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "doc":
                $response->headers->set('Content-Type', 'application/vnd.msword');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "docx":
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "ppt":
                $response->headers->set('Content-Type', 'application/vnd.mspowerpoint');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "pptx":
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "xls":
                $response->headers->set('Content-Type', 'application/vnd.ms-excel');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "xlsx":
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            case "pdf":
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
                break;
            default:
                $response->headers->set('Content-Type', 'application/octet-stream');
        }
        $response->setContent(file_get_contents($path));

        return $response;
    }



    /**
     * Finds and displays an ODF or PDF with Viewer.js; used for both Projects and Resources.
     *
     * @Route("/{courseid}/{id}/{view}/view_file", name="file_view")
     */
    public function viewAction(Request $request, $id)
    {
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $role = $this->getCourseRole($request);
        $review_file = $file->getReviewed();
        $markup = null;

        $course = $this->getCourse($request);
        $user = $this->getUser();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);

        if ($review_file) {
            $parent_file = $em->getRepository('MarcaFileBundle:File')->find($review_file->getId());
        } else {
            $parent_file = $file;
        }

        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        return $this->render('MarcaDocBundle:Doc:show.html.twig', array(
            'role' => $role,
            'roll' => $roll,
            'file' => $file,
            'parent_file' => $parent_file,
            'reviews' => $reviews,
            'markup' => $markup,
        ));

    }

    /**
     * Finds and displays an ODF or PDF with Viewer.js; used for both Projects and Resources.
     *
     * @Route("/{courseid}/{id}/view_file_ajax", name="file_view_ajax")
     */
    public function viewAjaxAction(Request $request, $id)
    {
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $role = $this->getCourseRole($request);
        $review_file = $file->getReviewed();
        $markup = null;

        $course = $this->getCourse($request);
        $user = $this->getUser();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);


        if ($review_file) {
            $parent_file = $em->getRepository('MarcaFileBundle:File')->find($review_file->getId());
        } else {
            $parent_file = $file;
        }

        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        return $this->render('MarcaDocBundle:Doc:show_ajax.html.twig', array(
            'role' => $role,
            'roll' => $roll,
            'file' => $file,
            'parent_file' => $parent_file,
            'reviews' => $reviews,
            'markup' => $markup,
        ));


    }

}
