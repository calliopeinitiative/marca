<?php

namespace Marca\DocBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $etherpadInstance = $this->get('etherpadlite');


        //sets up etherpad instance and either creates author or fetches existing etherpad author id for a given user
        $etherpad_id = $file->getEtherpaddoc();
        $etherpadInstance = $this->get('etherpadlite');
        $author = $etherpadInstance->createAuthorIfNotExistsFor($user->getId(), $user->getFirstname());
        $authorId = $author->authorID;


        //checks if etherpaddoc hasn't been set and, if it hasn't, creates a new etherpad and persists the id on the file object
        //for now, it just does that and leaves the old doc alone
        if($etherpad_id === null){
            $doc = $file->getDoc();
            $docName = uniqid($user->getId());
            $groupKey = str_shuffle($docName);
            $group = $etherpadInstance->createGroupIfNotExistsFor($groupKey);
            $groupId = $group->groupID;
            $etherpadInstance->createGroupPad($groupId, $docName);
            if($doc->getBody() != null){
                $taggedBody = "<html>".$doc->getBody()."</html>";
                $etherpadInstance->setHTML($groupId."$".$docName, $taggedBody);
            }

            $file->setEtherpaddoc($groupId."$".$docName);
            $file->setEtherpadgroup($groupId);
            $em->persist($file);
            $em->flush();

        }


        $doc = $file->getEtherpaddoc();
        $text = $etherpadInstance->getHTML($doc)->html;
        $countable = $etherpadInstance->getText($doc)->text;
        $count = str_word_count($countable);
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

        return $this->render('MarcaDocBundle:Doc:show.html.twig', array(
            'text'      => $text,
            'roll' => $roll,
            'role'      => $role,
            'count'=> $count,
            'file'        => $file,
            'parent_file'        => $parent_file,
            'markup' => $markup,
            'reviews' => $reviews,
            'local_resource' => $local_resource,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Doc entity.
     *
     * @Route("/{courseid}/{id}/show_ajax", name="doc_show_ajax")
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

        return $this->render('MarcaDocBundle:Doc:show_ajax.html.twig', array(
            'doc'      => $doc,
            'role'      => $role,
            'file'        => $file,
            'markup' => $markup,
        ));
    }



    /**
     * Displays a form to create a new Doc entity.
     *
     * @Route("/{courseid}/{resource}/{fileid}/review", name="doc_review")
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

        return $this->render('MarcaDocBundle:Doc:new.html.twig', array(
            'doc' => $doc,
            'fileid' => $fileid,
            'markupsets'      => $markupsets,
            'form'   => $form->createView()
        ));
    }


    /**
     * Displays a form to edit an existing Doc entity.
     *
     * @Route("/{courseid}/{id}/{view}/{submissionid}/edit", name="doc_edit", defaults={ "submissionid"= 0})
     * @Template()
     */
    public function editAction($courseid, $id, $submissionid)
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
        $file_owner = $file->getUser();
        $file_access = $file->getAccess();
        //allow access for the file creator, the instructor, or to public docs
        if($file_owner != $user && $file_access==0 && $role != 2){
            throw new AccessDeniedException();
        };
        //sets up etherpad instance and either creates author or fetches existing etherpad author id for a given user
        $etherpad_id = $file->getEtherpaddoc();
        $etherpadInstance = $this->get('etherpadlite');
        $author = $etherpadInstance->createAuthorIfNotExistsFor($user->getId(), $user->getFirstname());
        $authorId = $author->authorID;
        $groupId = $file->getEtherpadgroup();
        //time fetches current unix timestamp. 86400 = 1 day in seconds.
        $expires = time() + 86400;
        $etherpadSession = $etherpadInstance->createSession($groupId, $authorId, $expires);
        $markupsets = $course->getMarkupsets();
        $styleArray = array();
        foreach($markupsets as $markupset){
            foreach($markupset->getMarkup() as $markup) {
                $styleName = str_replace(" ", "_", $markup->getName());
                $styleName = $styleName . "_" . $markup->getId() . "_" . $markup->getColor();
                $etherpadInstance->customStyleNew($styleName, '.'.$styleName.'{background-color:'.$markup->getColor().'}');
                array_push($styleArray, $styleName);
            }
        }
        //if the student is working on a submission for an assignment, fetch the submission for passing to the controller
        if($submissionid != 0){
            $submission = $em->getRepository('MarcaAssignmentBundle:AssignmentSubmission')->find($submissionid);
        }
        //otherwise set submission to null
        else
        {
            $submission = null;
        }
        $etherpadInstance->customStyleSetStylesForPad($etherpad_id, $styleArray);

        $reviews = $em->getRepository('MarcaAssignmentBundle:Review')->findReviewsByFile($id);

        //create the response and then set the etherpad session cookie
        $response = $this->render('MarcaDocBundle:Doc:edit.html.twig', array(
            'etherpad_doc'      => $etherpad_id,
            'file'        => $file,
            'pages'        => $pages,
            'role'      => $role,
            'markupsets' => $markupsets,
            'reviews' => $reviews,
            'courseid' => $courseid,
            'submission' => $submission,
        ));
        $cookie_domain = $this->container->getParameter("etherpad_cookie_domain");
        $response->headers->setCookie(new Cookie('sessionID', $etherpadSession->sessionID, $expires, '/', $cookie_domain , false, false));
        return $response;
    }

    /**
     * Edits an existing Doc entity.
     *
     * @Route("/{courseid}/{id}/{view}/update", name="doc_update")
     * @Method("post")
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

            return $this->redirect($this->generateUrl('doc_show', array('id' => $id, 'courseid'=> $courseid, 'view' => $view)));
        }

        return $this->render('MarcaDocBundle:Doc:edit.html.twig', array(
            'doc'      => $doc,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * saves an etherpad doc
     * @Route("/{courseid}/{id}/{view}/update", name="doc_save")
     */
     public function saveAction($id, $courseid, $view)
     {
         $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
         $this->restrictAccessTo($allowed);

         $em = $this->getEm();

         //Post-processing of etherpad-docs

         $response = new RedirectResponse($this->generateUrl('doc_show', array('id' => $id, 'courseid'=> $courseid, 'view' => $view)));
         return $response;
     }

    /**
     * Saves via AJAX
     * @Route("/{courseid}/{id}/ajaxupdate", name="doc_ajax")
     * @Method("post")
     */
    public function ajaxUpdateAction($id)
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

        return $this->render('MarcaDocBundle:Doc:pdf.html.twig', array(
            'doc' => $doc,
            'ip' => $ip
        ));
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
