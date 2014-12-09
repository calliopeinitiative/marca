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
use Marca\FileBundle\Form\UploadType;
use Marca\FileBundle\Form\UploadReviewType;
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
     * Lists all Course entities.
     *
     * @Route("/{courseid}/{resource}/resources_sidebar", name="file_resources_sidebar", defaults={ "tag" = 0,"project" = 0})
     */
    public function createResources_sidebarAction($courseid, $resource)
    {
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);
        $default_project = $projects[0]->getId();
        $project = $default_project;
        $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();
        $projectForTags = $em->getRepository('MarcaCourseBundle:Project')->find($project);
        $courseForTags = $projectForTags->getCourse();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($courseForTags);
        return $this->render('MarcaFileBundle::resources_sidebar.html.twig', array(
            'projects' => $projects,
            'tags' => $tags,
            'systemtags' => $systemtags,
            'role' => $role
        ));
    }

    /**
     * Creates esi sidebar fragment for projects.
     *
     * @Route("/{courseid}/{resource}/files_sidebar", name="file_files_sidebar")
     */
    public function createFiles_sidebarAction($courseid, $resource)
    {
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);
        $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($course);
        return $this->render('MarcaFileBundle::files_sidebar.html.twig', array(
            'course' => $course,
            'projects' => $projects,
            'tags' => $tags,
            'systemtags' => $systemtags,
            'role' => $role
        ));
    }

    /**
     * Lists all File entities by User.
     *
     * @Route("/{courseid}/{userid}/{userindex}/{resource}/list", name="file_list", defaults={"resource" = 0, "userindex" = 0})
     */
    public function filesByUserAction($userid, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $session = $this->get('session');
        $request = $this->getRequest();
        $session->set('referrer', $request->getRequestUri());

        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $role = $this->getCourseRole();

        if ($userid==0) {
            $user = $this->getUser();
            $access = 2;
            $heading = 'My Files';
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
            $access = $role;
            $heading = 'Files by ' . $user->getFirstname() . ' ' . $user->getLastname() ;
        }

        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByUser($user, $course, $access);

        return $this->render('MarcaFileBundle:File:files_index.html.twig', array(
            'files' => $files,
            'role' => $role,
            'user' => $user,
            'course' => $course,
            'heading' => $heading
        ));
    }


    /**
     * Lists all File entities by User.
     *
     * @Route("/{courseid}/{userid}/{resource}/reviews", name="file_reviews")
     */
    public function reviewsByUserAction($userid, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $session = $this->get('session');
        $request = $this->getRequest();
        $session->set('referrer', $request->getRequestUri());

        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $role = $this->getCourseRole();

        if ($userid==0) {
            $user = $this->getUser();
            $access = 2;
            $heading = 'My Reviews';
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
            $access = $role;
            $heading = 'Reviews by ' . $user->getFirstname() . ' ' . $user->getLastname() ;
        }

        $files = $em->getRepository('MarcaFileBundle:File')->findReviewsByUser($user, $course, $access);

        return $this->render('MarcaFileBundle:File:reviews_index.html.twig', array(
            'files' => $files,
            'role' => $role,
            'user' => $user,
            'course' => $course,
            'heading' => $heading
        ));
    }

    /**
     * Lists all File entities by Project.
     *
     * @Route("/{courseid}/{project}/{resource}/filebyproject", name="file_listbyproject")
     */
    public function filesByProjectAction($project, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $role = $this->getCourseRole();
        $access = $role;

        $files = $em->getRepository('MarcaFileBundle:File')->findFilesByProject($project, $access);
        $project = $em->getRepository('MarcaCourseBundle:Project')->find($project);

        $session = $this->get('session');
        $request = $this->getRequest();

        if ($resource == 0) {
            $template = 'MarcaFileBundle:File:files_index.html.twig';
            $session->set('referrer', $request->getRequestUri());
        } else {
            $template = 'MarcaFileBundle:File:resources_index.html.twig';
            $session->set('resource_referrer', $request->getRequestUri());
        }

        $heading = $course->getName().': '. $project->getName();

        return $this->render($template, array(
            'files' => $files,
            'role' => $role,
            'course' => $course,
            'heading' => $heading
        ));
    }


    /**
     * Finds and displays a File entity.
     *
     * @Route("/{courseid}/{id}/{resource}/show_modal", name="file_show_modal")
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
        return $this->render('MarcaFileBundle:File:show_modal.html.twig', array('file' => $file, 'delete_form' => $deleteForm->createView(),));
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
        if (!$session) {
            $uri = '../../../file/1/recent/0/mine/0/0/0/list';
        } else {
            $uri = $session->get('referrer');
        }

        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        if ($file->getAccess() == 2) {
            $file->setAccess('0');
        } else {
            $file->setAccess('2');
        }
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
        if (!$session) {
            $uri = '../../../file/1/default/0/mine/0/0/0/list';
        } else {
            $uri = $session->get('referrer');
        }
        $user = $this->getUser();
        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $files = $em->getRepository('MarcaFileBundle:File')->findHidden($user, $course);
        foreach ($files as $file) {
            $file->setAccess('0');
            $em->persist($file);
        }
        $em->flush();

        return $this->redirect($uri);
    }


    /**
     * Displays a form to create a new File entity for a LINK listing.
     *
     * @Route("/{courseid}/{resource}/{type}/{fileid}/new_modal", name="file_new_modal", defaults={"resource" = 0, "fileid" = 0})
     */
    public function newModalAction($courseid, $resource, $type, $fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project = $course->getProjectDefault();
        $options = array('courseid' => $courseid, 'resource' => $resource);

        $file = new File();
        $file->setUser($user);
        $file->setCourse($course);
        if ($resource != 0) {
            $project = $em->getRepository('MarcaCourseBundle:Project')->findProjectByCourse($course, $resource);
            $file->setAccess(1);
        }
        $file->setProject($project);

        if ($type == 'link') {
            $form = $this->createForm(new LinkType($options), $file);
            return $this->render('MarcaFileBundle:File:new_modal.html.twig', array(
                'file' => $file,
                'resource' => $resource,
                'type' => $type,
                'tags' => $tags,
                'roll' => $roll,
                'course' => $course,
                'form' => $form->createView()
            ));
        } elseif ($type == 'doc') {
            $form = $this->createForm(new DocType($options), $file);
            return $this->render('MarcaFileBundle:File:new_modal.html.twig', array(
                'file' => $file,
                'resource' => $resource,
                'type' => $type,
                'tags' => $tags,
                'roll' => $roll,
                'course' => $course,
                'form' => $form->createView()
            ));
        } elseif ($type == 'review') {
            $file->setName('Review');
            if ($role == 2) {
                $file->setAccess('2');
            } else {
                $file->setAccess('0');
            }
            $doc = new Doc();
            $doc->setFile($file);
            $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
            $file->setReviewed($reviewed_file);
            $file->addTag($em->getRepository('MarcaTagBundle:Tag')->find(3));
            $doc->setBody($reviewed_file->getDoc()->getBody());
            $em->persist($doc);
            $em->persist($file);
            $em->flush();
            return $this->redirect($this->generateUrl('doc_edit', array('courseid' => $courseid, 'id' => $file->getId(), 'view' => 'app')));
        } elseif ($type == 'saveas') {
            $file->setName('SaveAs Document');
            $form = $this->createForm(new DocType($options), $file);
            return $this->render('MarcaFileBundle:File:new_modal.html.twig', array(
                'file' => $file,
                'resource' => $resource,
                'type' => $type,
                'tags' => $tags,
                'roll' => $roll,
                'course' => $course,
                'form' => $form->createView()
            ));
        }
    }



    /**
     * Creates a new File entity.
     *
     * @Route("/{courseid}/{resource}/{type}/{fileid}/create", defaults={ "fileid" = 0 }, name="file_create", defaults={"resource" = 0, "fileid" = 0})
     * @Method("post")
     */
    public function createAction($courseid, $resource, $type, $fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $file = new File();
        $em = $this->getEm();
        $user = $this->getUser();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $file->setUser($user);
        $file->setCourse($course);
        $options = array('courseid' => $courseid, 'resource' => $resource);
        $request = $this->getRequest();
        $postData = $request->get('marca_filebundle_filetype');
        $project = $postData['project'];
        $form = $this->createForm(new FileType($options), $file);
        $form->submit($request);
        if ($type == 'doc') {
            $doc = new Doc();
            $doc->setFile($file);
            $doc->setBody('<p> </p>');
        } elseif ($type == 'review') {

        } elseif ($type == 'saveas') {
            $doc = new Doc();
            $doc->setFile($file);
            $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
            $doc->setBody($reviewed_file->getDoc()->getBody());
        }
        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($file);
            if ($type == 'doc' || $type == 'review' || $type == 'instr_review' || $type == 'saveas') {
                $em->persist($doc);
            }
            $em->flush();

            if ($type == 'link') {
                return $this->redirect($this->generateUrl('file_list', array('courseid' => $courseid, 'id' => $file->getId(), 'scope' => 'mine', 'project' => $project, 'tag' => '0', 'userid' => '0', 'resource' => $resource, 'user' => '0')));
            } elseif ($type == 'doc' || $type == 'review' || $type == 'instr_review' || $type == 'saveas') {
                return $this->redirect($this->generateUrl('doc_edit', array('courseid' => $courseid, 'id' => $file->getId(), 'view' => 'app')));
            }

        }

        return $this->render('MarcaFileBundle:File:new.html.twig', array(
            'file' => $file,
            'resource' => $resource,
            'type' => $type,
            'tags' => $tags,
            'roll' => $roll,
            'course' => $course,
            'form' => $form->createView()
        ));
    }


    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{courseid}/{resource}/{id}/edit_modal", name="file_edit_modal", defaults={"resource" = 0, "fileid" = 0})
     */
    public function editModalAction($id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
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
            $editForm = $this->createForm(new LinkType($options), $file);
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
     * Edits an existing File entity.
     *
     * @Route("/{courseid}/{resource}/{id}/update", name="file_update", defaults={"resource" = 0, "fileid" = 0})
     * @Method("post")
     * @Template("MarcaFileBundle:File:edit.html.twig")
     */
    public function updateAction($id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        $user = $this->getUser();
        $role = $this->getCourseRole();

        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $course = $file->getCourse();
        $file_courseid = $course->getId();
        $options = array('courseid' => $file_courseid, 'resource' => $resource);

        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find File entity.');
        } elseif ($user != $file->getUser()) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new FileType($options), $file);

        $request = $this->getRequest();
        $postData = $request->get('marca_filebundle_filetype');
        $project = $postData['project'];
        $resource = $em->getRepository('MarcaCourseBundle:Project')->find($project);
        $resource = $resource->getResource();
        if (!$resource) {
            $resource = '0';
        }


        $editForm->handleRequest($request);

        $em->persist($file);
        $em->flush();

        $session = $this->getRequest()->getSession();
        if (!$session) {
            $uri = '../../../file/1/default/0/mine/0/0/0/list';
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
     * Deletes a File entity.
     *
     * @Route("/{courseid}/{resource}/{id}/delete", name="file_delete", defaults={"resource" = 0})
     * @Method("post")
     */
    public function deleteAction($id, $courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

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

        $session = $this->getRequest()->getSession();
        if (!$session) {
            $uri = '../../../file/1/default/0/mine/0/0/0/list';
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
     * Uploads a file with a Document entity.
     *
     * @Route("/{courseid}/{resource}/upload", name="file_upload", defaults={"resource" = 0})
     */
    public function uploadAction($courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole();
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
        $form = $this->createForm(new UploadType($options), $file);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->submit($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getEm();
                $em->persist($file);
                $em->flush();

                $session = $this->getRequest()->getSession();
                if (!$session) {
                    $uri = '../../../file/1/default/0/mine/0/0/0/list';
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
     * Uploads a file with a Document entity.
     *
     * @Route("/{courseid}/{fileid}/reviewupload", name="review_upload", defaults={"resource" = 0})
     */
    public function uploadReviewAction($courseid, $fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $resource = 0;
        $user = $this->getUser();
        $role = $this->getCourseRole();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $project = $course->getProjectDefault();
        $options = array('courseid' => $courseid, 'resource' => $resource, 'review' => 'yes', 'role' => $role);
        $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();

        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $file = new File();
        $file->setUser($user);
        $file->setCourse($course);
        $file->setName('Review');
        $file->setProject($project);
        if ($role == 2) {
            $file->setAccess('2');
        } else {
            $file->setAccess('0');
        }
        $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $file->setReviewed($reviewed_file);
        $file->addTag($em->getRepository('MarcaTagBundle:Tag')->find(3));
        $form = $this->createForm(new UploadReviewType($options), $file);

        $request = $this->getRequest();
        $postData = $request->get('marca_filebundle_filetype');
        $project = $postData['project'];
        if (!$resource) {
            $resource = '0';
        }


        if ($this->getRequest()->getMethod() === 'POST') {
            $form->submit($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getEm();
                $em->persist($file);
                $em->flush();
                return $this->redirect($this->generateUrl('file_list', array('courseid'=> $courseid,'project'=> $project, 'scope'=> 'reviews')));

            }

        }

        return $this->render('MarcaFileBundle:File:upload_modal.html.twig', array(
            'form' => $form->createView(),
            'tags' => $tags, 'systemtags' => $systemtags,
            'roll' => $roll,
            'course' => $course,));
    }


    /**
     * Finds and displays a File.
     *
     * @Route("/{courseid}/{id}/get/{filename}", name="file_get", defaults={"filename" = "name.ext"})
     *
     */
    public function getAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_PORTREVIEW, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

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
     * Finds and displays an ODF or PDF with Viewer.js
     *
     * @Route("/{courseid}/{id}/{view}/view_file", name="file_view")
     */
    public function viewAction($id)
    {
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $role = $this->getCourseRole();
        $review_file = $file->getReviewed();
        $markup = null;

        $course = $this->getCourse();
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
     * Finds and displays an ODF or PDF with Viewer.js
     *
     * @Route("/{courseid}/{id}/view_file_ajax", name="file_view_ajax")
     */
    public function view_ajaxAction($id)
    {
        $em = $this->getEm();
        $file = $em->getRepository('MarcaFileBundle:File')->find($id);
        $role = $this->getCourseRole();
        $review_file = $file->getReviewed();
        $markup = null;

        $course = $this->getCourse();
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
