<?php

namespace Marca\FileBundle\Controller;

use Marca\AssignmentBundle\Entity\AssignmentSubmission;
use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Marca\FileBundle\Entity\File;
use Marca\FileBundle\Form\FileType;
use Marca\FileBundle\Form\LinkType;
use Marca\FileBundle\Form\DocType;
use Marca\FileBundle\Form\UploadReviewType;
use Marca\TagBundle\Entity\Tagset;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Marca\DocBundle\Entity\Doc;

/**
 * File controller.
 *
 * @Route("/file")
 */
class FileController extends Controller
{

    /**
     * Creates esi sidebar fragment for projects.
     *
     * @Route("/{courseid}/{resource}/files_sidebar", name="file_files_sidebar")
     */
    public function createFilesSidebarAction($courseid, $resource)
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
            $heading = 'Reviews by me';
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
     * Lists all File entities by User.
     *
     * @Route("/{courseid}/{userid}/{resource}/reviews_for_user", name="file_reviews_for_user")
     */
    public function reviewsForUserAction($userid, $courseid)
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
            $access = $role;
            $heading = 'Reviews for me';
        }
        else {
            $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
            $access = $role;
            $heading = 'Reviews for ' . $user->getFirstname() . ' ' . $user->getLastname() ;
        }

        $files = $em->getRepository('MarcaFileBundle:File')->findReviewsForUser($user, $course, $access);

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
     * @Route("/{courseid}/{resource}/sharedfiles", name="file_listshared")
     */
    public function sharedFilesAction($courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $role = $this->getCourseRole();
        $access = $role;

        $files = $em->getRepository('MarcaFileBundle:File')->findSharedFiles($course, $access);

        $session = $this->get('session');
        $request = $this->getRequest();

        if ($resource == 0) {
            $template = 'MarcaFileBundle:File:files_index.html.twig';
            $session->set('referrer', $request->getRequestUri());
        } else {
            $template = 'MarcaFileBundle:File:resources_index.html.twig';
            $session->set('resource_referrer', $request->getRequestUri());
        }

        $heading = 'Shared Files';

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
     * @Route("/{courseid}/{resource}/{type}/{fileid}/{assignmentStageid}/new_modal", name="file_new_modal", defaults={"resource" = 0, "fileid" = 0, "assignmentStageid" = 0 })
     */
    public function newModalAction($courseid, $resource, $type, $fileid, $assignmentStageid)
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
                'assignmentStageid' => $assignmentStageid,
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
            $file->setProject($reviewed_file->getProject());
            $doc->setBody($reviewed_file->getDoc()->getBody());
            $em->persist($doc);
            $em->persist($file);
            $em->flush();
            return $this->redirect($this->generateUrl('doc_edit', array('courseid' => $courseid, 'id' => $file->getId(),
                'view' => 'window')));
        } elseif ($type == 'etherpad_review') {
            $file->setName('Review');
            if ($role == 2) {
                $file->setAccess('2');
            } else {
                $file->setAccess('0');
            }
            $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
            $file->setEtherpaddoc($reviewed_file->getEtherpaddoc());
            $file->setEtherpadgroup($reviewed_file->getEtherpadgroup());
            $file->setReviewed($reviewed_file);
            $file->addTag($em->getRepository('MarcaTagBundle:Tag')->find(3));
            $file->setProject($reviewed_file->getProject());
            $em->persist($file);
            $em->flush();
            return $this->redirect($this->generateUrl('doc_edit', array('courseid' => $courseid, 'id' => $file->getId(),
                'view' => 'window')));
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
     * @Route("/{courseid}/{resource}/{type}/{fileid}/{assignmentStageid}/create", defaults={ "fileid" = 0 }, name="file_create", defaults={"resource" = 0, "fileid" = 0, "assignmentStageid" = 0})
     * @Method("post")
     */
    public function createAction($courseid, $resource, $type, $fileid, $assignmentStageid)
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
        $form = $this->createForm(new FileType($options), $file);
        $form->submit($request);
        if ($type == 'doc') {
            if($assignmentStageid != 0){
                $assignmentSubmission = new AssignmentSubmission();
                $assignmentStage = $em->getRepository('MarcaAssignmentBundle:AssignmentStage')->find($assignmentStageid);
                $assignmentSubmission->setStage($assignmentStage);
                $assignmentSubmission->setUser($user);
                $assignmentSubmission->setCreated(new \DateTime("now"));
                $file->addAssignmentSubmission($assignmentSubmission);
                $em->persist($assignmentSubmission);
            }
            else {
                $assignmentSubmission = null;
            }
            $docName = uniqid($user->getId());
            $groupKey = str_shuffle($docName);
            $etherpad_instance = $this->get('etherpadlite');
            $group = $etherpad_instance->createGroupIfNotExistsFor($groupKey);
            $groupId = $group->groupID;
            $etherpad_instance->createGroupPad($groupId, $docName);
            $file->setEtherpaddoc($groupId."$".$docName);
            $file->setEtherpadgroup($groupId);
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
            if ($type == 'review' || $type == 'instr_review' || $type == 'saveas') {
                $em->persist($doc);
            }
            $em->flush();
            if($assignmentSubmission){
                $submissionid = $assignmentSubmission->getId();
            }
            else {
                $submissionid = 0;
            }

            if ($type == 'link') {
                return $this->redirect($this->generateUrl('file_list', array('courseid' => $courseid, 'id' => $file->getId(), 'userid' => '0', 'resource' => $resource, 'user' => '0')));
            } elseif ($type == 'doc' || $type == 'review' || $type == 'instr_review' || $type == 'saveas') {
                return $this->redirect($this->generateUrl('doc_edit', array('courseid' => $courseid, 'id' => $file->getId(),
                    'view' => 'app', 'submissionid' => $submissionid )));
            }

        }

        return $this->render('MarcaFileBundle:File:new.html.twig', array(
            'file' => $file,
            'assignmentStageId' => $assignmentStageid,
            'resource' => $resource,
            'type' => $type,
            'tags' => $tags,
            'roll' => $roll,
            'course' => $course,
            'form' => $form->createView()
        ));
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
        $options = array('courseid' => $courseid, 'resource' => $resource, 'review' => 'yes', 'role' => $role);
        $systemtags = $em->getRepository('MarcaTagBundle:Tagset')->findSystemTags();
        $tags = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByCourse($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $reviewed_file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $file = new File();
        $file->setUser($user);
        $file->setCourse($course);
        $file->setName('Review');
        $file->setProject($reviewed_file->getProject());
        if ($role == 2) {
            $file->setAccess('2');
        } else {
            $file->setAccess('0');
        }
        $file->setReviewed($reviewed_file);
        $file->addTag($em->getRepository('MarcaTagBundle:Tag')->find(3));
        $form = $this->createForm(new UploadReviewType($options), $file);
        if (!$resource) {
            $resource = '0';
        }


        if ($this->getRequest()->getMethod() === 'POST') {
            $form->submit($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getEm();
                $em->persist($file);
                $em->flush();
                return $this->redirect($this->generateUrl('file_reviews', array('courseid' => $courseid, 'id' => $file->getId(), 'userid' => '0',
                    'resource' => $resource, 'user' => '0')));

            }

        }

        return $this->render('MarcaFileBundle:File:upload_modal.html.twig', array(
            'form' => $form->createView(),
            'tags' => $tags, 'systemtags' => $systemtags,
            'roll' => $roll,
            'course' => $course,));
    }

}
